<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'table_games';

    protected $fillable = [
        'rawg_id',
        'name',
        'slug',
        'released',
        'background_image',
        'rating',
        'metacritic',
        'playtime',
        'description',
    ];

    protected $casts = [
        'released' => 'date',
        'rating' => 'float',
        'metacritic' => 'integer',
        'playtime' => 'integer',
    ];

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'table_likes', 'game_id', 'user_id');
    }

    public function watchlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'table_watchlist', 'game_id', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'game_id');
    }
}