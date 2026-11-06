<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $table = 'table_lists';
    
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(ListItem::class, 'list_id')->orderBy('position');
    }
    
    public function getRouteKeyName()
    {
        return 'id';
    }
}