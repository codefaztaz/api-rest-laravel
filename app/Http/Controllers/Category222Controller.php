<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\Category;

class CategoryController extends Controller
{
    public function pruebas()
    {
        return "controlador de categoria";
    }

    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'code' => 200,
            'status' =>'success',
            'categories' => $categories
        ]);
    }
}
