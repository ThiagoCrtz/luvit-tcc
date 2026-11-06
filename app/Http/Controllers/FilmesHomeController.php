<?php

namespace App\Http\Controllers;

use App\Services\TmdbClient;
use Illuminate\Http\Request;

class FilmesHomeController extends Controller
{
    public function __construct(private TmdbClient $tmdb) {}

    public function index(Request $request)
    {
        $ignoreGenres = ['Faroeste'];

        $genres = $this->tmdb->getGenres();
        if ($request->filled('genre')) {
            $request->validate(['genre' => 'integer|min:1']);
            $genres = array_values(array_filter($genres, fn ($genre) => $genre['id'] == $request->genre));
        }
        shuffle($genres);
        $trendingMovies = $this->tmdb->popular();

        $moviesByGenre = [];

        if ($request->boolean('popular')) {
            $moviesByGenre['Em alta'] = $trendingMovies['results'] ?? [];

            return view('filmes.index', compact('moviesByGenre', 'trendingMovies'));
        }

        foreach ($genres as $genre) {
            $res    = $this->tmdb->discoverByGenre($genre['id']);
            $movies = $res['results'] ?? [];

            shuffle($movies);

            foreach ($movies as &$movie) {
                $movie['main_genre'] = $this->tmdb->getMainGenre($movie['genre_ids'] ?? [], $ignoreGenres);
            }
            unset($movie);

            $moviesByGenre[$genre['name']] = $movies;
        }

        return view('filmes.index', compact('moviesByGenre', 'trendingMovies'));
    }
}
