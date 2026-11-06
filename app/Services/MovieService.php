<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MovieService
{
    public function __construct(private TmdbClient $tmdbClient) {}

    public function createOrUpdateMovie(int $tmdbId): Movie
    {
        if ($movie = Movie::where('tmdb_id', $tmdbId)->first()) {
            return $movie;
        }
        return Cache::remember("movie_local:{$tmdbId}", now()->addDay(), function () use ($tmdbId) {
            $movieData = $this->tmdbClient->movieDetails($tmdbId);
            abort_unless(!empty($movieData['id']), 404, 'Filme não encontrado ou catálogo indisponível.');

            return DB::transaction(fn () =>
                Movie::updateOrCreate(
                    ['tmdb_id' => $tmdbId],
                    $this->mapToMovieColumns($movieData)
                )
            );
        });
    }

    public function ensureFromTmdbData(array $data): Movie
    {
        $tmdbId = $data['id'] ?? null;
        if (!$tmdbId) {
            abort(404, 'Filme não encontrado ou catálogo indisponível.');
        }

        return Movie::updateOrCreate(
            ['tmdb_id' => $tmdbId],
            $this->mapToMovieColumns($data)
        );
    }

    private function mapToMovieColumns(array $md): array
    {
        return [
            'title'        => $md['title'] ?? $md['name'] ?? 'Sem título',
            'description'  => $md['overview'] ?? null,
            'poster'       => !empty($md['poster_path']) ? "https://image.tmdb.org/t/p/w500{$md['poster_path']}" : null,
            'genre'        => $this->mapGenresToString($md),
            'release_date' => ($md['release_date'] ?? null) ?: null,
            'rating'       => isset($md['vote_average']) ? round((float)$md['vote_average'], 1) : null,
            'duration'     => $md['runtime'] ?? null,
            'language'     => isset($md['original_language']) ? strtoupper($md['original_language']) : null,
            'director'     => $this->extractDirector($md),
            'cast'         => $this->extractCast($md),
            'imdb_id'      => $md['imdb_id'] ?? null,
        ];
    }

    private function mapGenresToString(array $md): ?string
    {
        if (!empty($md['genres']) && is_array($md['genres'])) {
            return collect($md['genres'])->pluck('name')->implode(', ');
        }

        if (!empty($md['genre_ids'])) {
            $genres = $this->tmdbClient->getGenres();
            $map    = collect($genres)->pluck('name', 'id')->toArray();

            return collect($md['genre_ids'])
                ->map(fn ($id) => $map[$id] ?? null)
                ->filter()
                ->implode(', ');
        }

        return null;
        }
    private function extractDirector(array $md): ?string
    {
        if (empty($md['credits']['crew'])) return null;

        foreach ($md['credits']['crew'] as $crew) {
            if (($crew['job'] ?? '') === 'Director') {
                return $crew['name'];
            }
        }
        return null;
    }

    private function extractCast(array $md): ?string
    {
        if (empty($md['credits']['cast'])) return null;

        return collect($md['credits']['cast'])
            ->take(5)
            ->pluck('name')
            ->implode(', ');
    }
}
