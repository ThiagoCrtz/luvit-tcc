<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $table = 'table_watchlist';

    protected $fillable = [
        'user_id',
        'movie_id',
        'game_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
