<?php

namespace App\Http\Controllers;
use App\Models\Post;
USE App\Category;

use Illuminate\Http\Request;

class PruebasController extends Controller
{
    //
    public function testOrm()
    {
        $posts = Post::all();
        var_dump($posts);
        die();
    }
}
