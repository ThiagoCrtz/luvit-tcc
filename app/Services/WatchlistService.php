<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

class WatchlistService
{
    public function toggle($item, int $userId): array
    {
        $isMovie = $item instanceof Movie;
        $foreignKey = $isMovie ? 'movie_id' : 'game_id';

        $exists = DB::table('table_watchlist')
            ->where('user_id', $userId)
            ->where($foreignKey, $item->id)
            ->exists();

        if ($exists) {
            DB::table('table_watchlist')
                ->where('user_id', $userId)
                ->where($foreignKey, $item->id)
                ->delete();

            return ['added' => false];
        } else {
            DB::table('table_watchlist')->insert([
                'user_id'   => $userId,
                $foreignKey => $item->id,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);

            return ['added' => true];
        }
    }

    public function exists($item, int $userId): bool
    {
        $isMovie = $item instanceof Movie;
        $foreignKey = $isMovie ? 'movie_id' : 'game_id';

        return DB::table('table_watchlist')
            ->where($foreignKey, $item->id)
            ->where('user_id', $userId)
            ->exists();
    }
}