<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $talbe = 'categories';

    // Relacion de uno a muchos
    public fuction posts()
    {
        return $this->hasMany('App\Post');
    }
}
