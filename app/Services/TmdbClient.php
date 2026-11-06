<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class TmdbClient
{
    private string $base = 'https://api.themoviedb.org/3';
    private array $baseQuery;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->baseQuery = [
            'api_key'  => config('services.tmdb.key'),
            'language' => config('services.tmdb.lang', 'pt-BR'),
        ];
    }

    private function get(string $path, array $query = [], $ttl = null): array
    {
        if (!config('services.tmdb.key')) return [];
        $merged = array_merge($this->baseQuery, $query);
        $cacheKey = 'tmdb:' . md5($path . http_build_query($merged));
        $ttl = $ttl ?? now()->addHour();

        try {
            return Cache::remember($cacheKey, $ttl, function () use ($path, $merged) {
                $response = Http::baseUrl($this->base)
                    ->timeout(3)      // Reduzido de 5s
                    ->retry(1, 100)   // Menos retries
                    ->get($path, $merged);

                $response->throw();
                return $response->json() ?? [];
            });
        } catch (RequestException|ConnectionException $e) {
            $this->logger->warning("TMDB GET failed: {$path}", ['status' => $e instanceof RequestException ? $e->response->status() : null]);
            return [];
        }
    }

    public function movieDetails(int|string $tmdbId, ?array $append = null): array
    {
        $append = $append ?? explode(',', (string)config('services.tmdb.append', 'videos,credits'));
        $appendStr = implode(',', $append);

        return $this->get(
            "/movie/{$tmdbId}", 
            ['append_to_response' => $appendStr], 
            now()->addDay()
        );
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        try {
            $res = Http::baseUrl($this->base)
                ->timeout(3)
                ->retry(1, 100)
                ->get('/search/movie', array_merge($this->baseQuery, [
                    'query' => $query,
                    'page'  => $page
                ]));

            $res->throw();
            return $res->json()['results'] ?? [];
        } catch (RequestException|ConnectionException $e) {
            $this->logger->warning("TMDB search failed", ['query' => $query]);
            return [];
        }
    }

    public function popular(int $page = 1): array
    {
        return $this->get('/movie/popular', ['page' => $page], now()->addHour());
    }

    public function discoverByGenre(int|string $genreId, int $page = 1): array
    {
        return $this->get('/discover/movie', [
            'with_genres' => $genreId,
            'sort_by'     => 'popularity.desc',
            'page'        => $page,
        ], now()->addHour());
    }


    public function getGenres(): array
    {
        return $this->get('/genre/movie/list', [], now()->addDay())['genres'] ?? [];
    }

    public function getMainGenre(array $genreIds, array $ignoreGenres = []): ?string
    {
        if (empty($genreIds)) return null;

        $genres = $this->getGenres();
        $genresMap = collect($genres)->pluck('name', 'id')->toArray();

        foreach ($genreIds as $gid) {
            if (isset($genresMap[$gid]) && !in_array($genresMap[$gid], $ignoreGenres, true)) {
                return $genresMap[$gid];
            }
        }
        return null;
    }
}