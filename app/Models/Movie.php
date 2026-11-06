<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'table_movies';

    protected $fillable = [
        'id',
        'tmdb_id',
        'title',
        'description',
        'poster',
        'genre',
        'release_date',
        'rating',
        'duration',
        'language',
        'director',
        'cast',
        'imdb_id'
    ];

    protected $dates = ['release_date'];

    public function likes()
    {
        return $this->hasMany(Like::class, 'movie_id');
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'table_likes', 'movie_id', 'user_id')->withTimestamps();
    }

    // Watchlist
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class, 'movie_id');
    }

    public function inWatchlistsByUsers()
    {
        return $this->belongsToMany(User::class, 'table_watchlist', 'movie_id', 'user_id')->withTimestamps();
    }

    // Reviews corretas
    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id');
    }

    public function getPosterPathAttribute()
    {
        return $this->poster;
    }

}
