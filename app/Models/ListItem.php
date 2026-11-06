<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $table = 'table_list_items';

    protected $touches = ['list'];
    
    protected $fillable = [
        'list_id',
        'media_type',
        'media_id',
        'title',
        'poster_path',
        'position'
    ];

    public function list()
    {
        return $this->belongsTo(ListModel::class, 'list_id');
    }
}