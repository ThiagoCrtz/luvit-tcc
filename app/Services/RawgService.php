<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class RawgService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct(private IgdbService $igdb)
    {
        $this->baseUrl = config('services.rawg.base_url');
        $this->apiKey = config('services.rawg.key');
    }

    protected function request($endpoint, $params = [])
    {
        if (!$this->apiKey) {
            return [];
        }
        $params['key'] = $this->apiKey;
        $cacheKey = 'rawg:' . md5($endpoint . http_build_query($params));
        try {
            return Cache::remember($cacheKey, now()->addHour(), function () use ($endpoint, $params) {
                return Http::acceptJson()->connectTimeout(5)->timeout(12)
                    ->get(rtrim($this->baseUrl, '/') . '/' . $endpoint, $params)
                    ->throw()->json() ?? [];
            });
        } catch (RequestException $e) {
            Log::warning('RAWG request failed', ['endpoint' => $endpoint, 'status' => $e->response?->status()]);
            return [];
        } catch (ConnectionException $e) {
            Log::warning('RAWG connection failed', ['endpoint' => $endpoint]);
            return [];
        }
    }

    public function getPopularGames($page = 1)
    {
        if ($this->igdb->configured() && ($games = $this->igdb->popular($page))) {
            return $games;
        }

        $response = $this->request('games', [
            'page' => $page,
            'page_size' => 24,
            'ordering' => '-rating',
        ]);

        return $response;
    }

    public function searchGames($query)
    {
        if ($this->igdb->configured() && ($games = $this->igdb->search($query))) {
            return $games;
        }

        $response = $this->request('games', [
            'search' => $query,
        ]);

        if (isset($response['results'])) {
            return $response;
        }

        return ['results' => Game::query()
            ->where('name', 'like', "%{$query}%")
            ->limit(20)
            ->get()
            ->map(fn (Game $game) => $this->localGameData($game))
            ->all()];
    }

    public function getGameDetails($id)
    {
        if ($id >= IgdbService::ID_OFFSET) {
            $response = $this->igdb->details((int) $id);

            if (!empty($response['id'])) {
                return $response;
            }

            $game = Game::where('rawg_id', $id)->first();

            return $game ? $this->localGameData($game) : [];
        }

        if ($this->igdb->configured() && ($game = Game::where('rawg_id', $id)->first())) {
            return $this->localGameData($game);
        }

        $response = $this->request("games/{$id}");

        if (!empty($response['id'])) {
            return $response;
        }

        $game = Game::where('rawg_id', $id)->first();

        return $game ? $this->localGameData($game) : [];
    }

    private function localGameData(Game $game): array
    {
        return [
            'id' => $game->rawg_id,
            'provider' => $game->rawg_id >= IgdbService::ID_OFFSET ? 'igdb' : 'rawg',
            'name' => $game->name,
            'slug' => $game->slug,
            'released' => $game->released?->format('Y-m-d'),
            'background_image' => $game->background_image,
            'rating' => $game->rating,
            'metacritic' => $game->metacritic,
            'playtime' => $game->playtime,
            'description_raw' => $game->description,
            'genres' => [],
            'platforms' => [],
        ];
    }
}
