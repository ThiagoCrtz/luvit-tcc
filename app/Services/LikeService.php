<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Game;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

class LikeService
{

    public function toggle($item, int $userId): array
    {
        $isMovie = $item instanceof Movie;
        $foreignKey = $isMovie ? 'movie_id' : 'game_id';

        return DB::transaction(function() use ($item, $userId, $foreignKey) {

            $existing = Like::where($foreignKey, $item->id)
                            ->where('user_id', $userId)
                            ->first();

            if ($existing) {
                $existing->delete();
                $liked = false;
            } else {
                Like::create([
                    'user_id' => $userId,
                    $foreignKey => $item->id
                ]);
                $liked = true;
            }
            $likeCount = Like::where($foreignKey, $item->id)->count();

            return [
                'liked' => $liked,
                'likeCount' => $likeCount
            ];
        });
    }

    public function exists($item, int $userId): bool
    {
        $isMovie = $item instanceof Movie;
        $foreignKey = $isMovie ? 'movie_id' : 'game_id';

        return Like::where($foreignKey, $item->id)
                    ->where('user_id', $userId)
                    ->exists();
    }

    public function count($item): int
    {
        $isMovie = $item instanceof Movie;
        $foreignKey = $isMovie ? 'movie_id' : 'game_id';

        return Like::where($foreignKey, $item->id)->count();
    }
}
