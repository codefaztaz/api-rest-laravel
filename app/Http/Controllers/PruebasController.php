<?php

namespace App\Http\Controllers;
use App\Models\Post;
USE App\Models\Category;

use Illuminate\Http\Request;

class PruebasController extends Controller
{
    //
    public function testOrm()
    {
        $posts = Post::all();
        foreach($posts as $post)
        {
            echo "<h1>".$post->title."</h1>";
            echo "<span style='color:gray;'>".($post->user->name) ."</span>";
            echo "<p>". ($post->category->name)."</p>";
            echo "<p>".$post->content."</p>";
            echo "<hr>";
        }

        $categories = Category::all();
        foreach($categories as $category)
        {
            echo "<h1>". ($category->name). "</h1>";
        }
        
        die();
    }
}
