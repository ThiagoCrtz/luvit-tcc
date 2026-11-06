<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $table = 'table_reviews';
    
    protected $fillable = [
        'user_id',
        'movie_id',
        'game_id', 
        'review',
        'rating',
        'parent_id' 
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
    
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
    
    public function parent()
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }
    
    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }
}