<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';

    // relacion de uno a muchos inversa (muchos a uno)
    public function user()
    {
        return $this->belongTo('App\User', 'user_id');
    }
    public function category()
    {
        return $this->belongTo('App\Category', 'category_id');
    }
}
