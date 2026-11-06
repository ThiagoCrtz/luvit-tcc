<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ListModel;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'table_users';

    protected $fillable = [
        'name',
        'arroba',
        'email',
        'password',
        'profile_picture',
        'bio',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function likedMovies()
    {
        return $this->belongsToMany(Movie::class, 'table_likes', 'user_id', 'movie_id')->withTimestamps();
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        if (!$this->profile_picture) return null;

        return str_starts_with($this->profile_picture, 'https://')
            ? $this->profile_picture
            : asset('storage/' . $this->profile_picture);
    }

    public function watchlistMovies()
    {
        return $this->belongsToMany(Movie::class, 'table_watchlist', 'user_id', 'movie_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function gameReviews()
    {
        return $this->hasMany(Review::class, 'user_id')->whereNotNull('game_id');
    }

    public function likedGames()
    {
        return $this->belongsToMany(Game::class, 'table_likes', 'user_id', 'game_id')->withTimestamps();
    }
    
    public function watchlistGames()
    {
        return $this->belongsToMany(Game::class, 'table_watchlist', 'user_id', 'game_id')->withTimestamps();
    }
    public function lists()
    {
        return $this->hasMany(ListModel::class, 'user_id');
}

    public function getRouteKeyName()
    {
        return 'arroba';
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'table_follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'table_follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function follow($userId)
    {
        if ($userId == $this->id) {
            return false;
        }
        
        if (!$this->isFollowing($userId)) {
            $this->following()->attach($userId);
            return true;
        }
        return false;
    }

    public function unfollow($userId)
    {
        if ($this->isFollowing($userId)) {
            $this->following()->detach($userId);
            return true;
        }
        return false;
    }

    public function followingCount()
    {
        return $this->following()->count();
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }

}
