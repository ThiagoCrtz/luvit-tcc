<?php

namespace App\Http\Controllers;

use App\Services\TmdbClient;
use App\Services\RawgService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    protected $tmdb;
    protected $rawg;

    public function __construct(TmdbClient $tmdb, RawgService $rawg)
    {
        $this->tmdb = $tmdb;
        $this->rawg = $rawg;
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:150',
            'exclude' => 'nullable|in:user,movie,game',
        ]);
        $query = trim($request->input('query', ''));
        $exclude = $request->input('exclude'); 

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'search:' . md5(strtolower($query) . ':' . $exclude);
        
        $results = $this->performSearch($query, $exclude);

        return response()->json($results);
    }

    private function performSearch(string $query, ?string $exclude = null): array
    {
        $users = ($exclude !== 'user') ? $this->searchUsers($query) : [];

        $movies = $exclude === 'movie' ? [] : $this->processMovies($this->tmdb->searchMovies($query));
        $games = $exclude === 'game' ? [] : $this->processGames($this->rawg->searchGames($query)['results'] ?? []);
        
        return $this->interleaveResults($movies, $games, $users);
    }

    private function searchUsers(string $query): array
    {
        $users = User::where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('arroba', 'LIKE', "%{$query}%");
        })
        ->limit(3)
        ->get();

        return $users->map(function($user) {
            return [
                'id' => $user->id,
                'arroba' => $user->arroba,
                'title' => $user->name,
                'poster_path' => $user->profile_picture_url,
                'type' => 'user'
            ];
        })->toArray();
    }

    private function processMovies($response): array
    {
        $moviesData = $response;
        $genreMap = $this->getCachedGenres();
        $movies = [];
        
        foreach (array_slice($moviesData, 0, 4) as $movie) {
            $genres = $this->mapGenres($movie['genre_ids'] ?? [], $genreMap);
            
            $movies[] = [
                'id' => $movie['id'],
                'title' => $movie['title'] ?? 'Sem título',
                'poster_path' => !empty($movie['poster_path']) 
                    ? "https://image.tmdb.org/t/p/w92{$movie['poster_path']}" 
                    : null,
                'release_date' => isset($movie['release_date']) 
                    ? substr($movie['release_date'], 0, 4) 
                    : null,
                'vote_average' => isset($movie['vote_average']) 
                    ? round($movie['vote_average'], 1) 
                    : null,
                'genre_ids' => $genres,
                'type' => 'movie'
            ];
        }

        return $movies;
    }

    private function processGames($response): array
    {
        $gamesData = $response;
        $games = [];
        
        foreach (array_slice($gamesData, 0, 4) as $game) {
            $genres = [];
            if (!empty($game['genres'])) {
                foreach (array_slice($game['genres'], 0, 2) as $genre) {
                    $genres[] = $this->translateGameGenre($genre['name']);
                }
            }

            $games[] = [
                'id' => $game['id'],
                'title' => $game['name'] ?? 'Sem título',
                'poster_path' => $game['background_image'] ?? null,
                'release_date' => isset($game['released']) 
                    ? substr($game['released'], 0, 4) 
                    : null,
                'vote_average' => isset($game['rating']) 
                    ? round($game['rating'], 1) 
                    : null,
                'genre_ids' => $genres,
                'type' => 'game'
            ];
        }

        return $games;
    }

    private function interleaveResults(array $movies, array $games, array $users): array
    {
        $combined = [];
        
        foreach ($users as $user) {
            $combined[] = $user;
        }
        
        $maxCount = max(count($movies), count($games));
        for ($i = 0; $i < $maxCount; $i++) {
            if (isset($movies[$i])) $combined[] = $movies[$i];
            if (isset($games[$i])) $combined[] = $games[$i];
        }

        return array_slice($combined, 0, 10);
    }

    private function getCachedGenres(): array
    {
        return Cache::remember('tmdb:genres', now()->addDay(), function () {
            try {
                $genres = $this->tmdb->getGenres();
                $map = [];
                foreach ($genres as $genre) {
                    $map[$genre['id']] = $genre['name'];
                }
                return $map;
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    private function mapGenres(array $genreIds, array $genreMap): array
    {
        $genres = [];
        $count = 0;
        
        foreach ($genreIds as $id) {
            if ($count >= 2) break;
            if (isset($genreMap[$id])) {
                $genres[] = $genreMap[$id];
                $count++;
            }
        }
        
        return $genres;
    }

    private function translateGameGenre(string $genre): string
    {
        $translations = [
            'Action' => 'Ação',
            'Adventure' => 'Aventura',
            'RPG' => 'RPG',
            'Shooter' => 'Tiro',
            'Puzzle' => 'Quebra-cabeça',
            'Sports' => 'Esportes',
            'Racing' => 'Corrida',
            'Strategy' => 'Estratégia',
            'Simulation' => 'Simulação',
            'Fighting' => 'Luta',
            'Platformer' => 'Plataforma',
            'Casual' => 'Casual',
            'Horror' => 'Terror',
        ];

        return $translations[$genre] ?? $genre;
    }
}
