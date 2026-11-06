<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IgdbService
{
    // shortcut: Keep existing numeric game routes and list IDs; replace with provider IDs if more sources are added.
    public const ID_OFFSET = 1000000000000;

    private const FIELDS = 'name,slug,summary,first_release_date,cover.image_id,rating,rating_count,genres.name,platforms.name';

    public function configured(): bool
    {
        return filled(config('services.igdb.client_id')) && filled(config('services.igdb.client_secret'));
    }

    public function popular(int $page = 1): array
    {
        $offset = max(0, $page - 1) * 24;
        $games = $this->request('fields '.self::FIELDS.'; where game_type = 0 & rating_count > 100 & cover != null & version_parent = null; sort rating_count desc; limit 24; offset '.$offset.';');

        return $games ? ['results' => array_map($this->mapGame(...), $games), 'count' => count($games), 'provider' => 'igdb'] : [];
    }

    public function search(string $query): array
    {
        $escaped = str_replace(['\\', '"'], ['\\\\', '\\"'], $query);
        $games = $this->request('search "'.$escaped.'"; fields '.self::FIELDS.'; where game_type = 0 & version_parent = null; limit 20;');

        return $games ? ['results' => array_map($this->mapGame(...), $games)] : [];
    }

    public function details(int $id): array
    {
        if ($id < self::ID_OFFSET) {
            return [];
        }

        $games = $this->request('fields '.self::FIELDS.'; where id = '.($id - self::ID_OFFSET).'; limit 1;');

        return $games ? $this->mapGame($games[0]) : [];
    }

    private function request(string $query): array
    {
        if (!$this->configured()) {
            return [];
        }

        try {
            return Cache::remember('igdb:'.md5($query), now()->addHour(), function () use ($query) {
                $token = Cache::remember('igdb:token', now()->addHour(), function () {
                    return Http::asForm()->connectTimeout(5)->timeout(12)
                        ->post('https://id.twitch.tv/oauth2/token', [
                            'client_id' => config('services.igdb.client_id'),
                            'client_secret' => config('services.igdb.client_secret'),
                            'grant_type' => 'client_credentials',
                        ])->throw()->json('access_token');
                });

                return Http::withHeaders([
                    'Client-ID' => config('services.igdb.client_id'),
                    'Authorization' => 'Bearer '.$token,
                ])->withBody($query, 'text/plain')->connectTimeout(5)->timeout(12)
                    ->post('https://api.igdb.com/v4/games')->throw()->json() ?? [];
            });
        } catch (RequestException|ConnectionException $e) {
            Log::warning('IGDB request failed', ['status' => $e instanceof RequestException ? $e->response?->status() : null]);

            return [];
        }
    }

    private function mapGame(array $game): array
    {
        $imageId = $game['cover']['image_id'] ?? null;

        return [
            'id' => self::ID_OFFSET + $game['id'],
            'provider' => 'igdb',
            'name' => $game['name'] ?? 'Jogo sem nome',
            'slug' => $game['slug'] ?? null,
            'background_image' => $imageId ? "https://images.igdb.com/igdb/image/upload/t_cover_big/{$imageId}.jpg" : null,
            'released' => isset($game['first_release_date']) ? gmdate('Y-m-d', $game['first_release_date']) : null,
            'rating' => isset($game['rating']) ? round($game['rating'] / 20, 2) : null,
            'metacritic' => null,
            'playtime' => null,
            'description_raw' => $game['summary'] ?? null,
            'genres' => $game['genres'] ?? [],
            'platforms' => array_map(fn ($platform) => ['platform' => $platform], $game['platforms'] ?? []),
        ];
    }
}
