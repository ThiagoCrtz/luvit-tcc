<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function getProfileData(User $user): array
    {
        $movieData = fn ($movie) => [
            'id' => $movie->tmdb_id, 'title' => $movie->title,
            'poster_path' => $movie->poster, 'release_date' => $movie->release_date,
        ];
        $gameData = fn ($game) => [
            'id' => $game->rawg_id, 'name' => $game->name,
            'background_image' => $game->background_image,
            'released' => $game->released?->format('Y-m-d'),
        ];

        return [
            'likedMovies' => $user->likedMovies()->get()->map($movieData)->all(),
            'watchlistMovies' => $user->watchlistMovies()->get()->map($movieData)->all(),
            'likedGames' => $user->likedGames()->get()->map($gameData)->all(),
            'watchlistGames' => $user->watchlistGames()->get()->map($gameData)->all(),
            'reviews' => $this->getCommentsForUser($user),
            'lists' => $user->lists()->with('items')->get(),
        ];
    }

    public function getCommentsForUser(User $user): Collection
    {
        return $user->reviews()->with(['movie', 'game'])->latest()->get();
    }
}
