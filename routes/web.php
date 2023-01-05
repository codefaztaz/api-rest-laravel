<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\ApiAuthMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// test routes

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-orm', [PruebasController::class, 'testOrm']);


// API routes

// test routes

Route::get('/usuario/pruebas', [UserController::class, 'pruebas'] );
Route::get('/categoria/pruebas', [UserController::class, 'pruebas'] );
Route::get('/post/pruebas', [UserController::class, 'pruebas'] );

// User controller routes

Route::get('token', [UserController::class, 'showToken']);
Route::post('api/register', [UserController::class, 'register']);
Route::post('api/login',[UserController::class, 'login']);
Route::put('api/user/update',[UserController::class, 'update']);
Route::post('api/user/upload',[UserController::class, 'upload'])->middleware(['api.auth']);