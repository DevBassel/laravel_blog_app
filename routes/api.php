<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Blogs;
use App\Http\Controllers\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);



// auth routes
// ----------------------------------------------------------------
//                          Blogs Routes
// ----------------------------------------------------------------
Route::middleware('auth:sanctum')->prefix('blogs')->group(function () {
    Route::post('/', Blogs::class . '@create');
    Route::post('/like/{blog_id}', Blogs::class . '@toggleLike');
    Route::get('/', Blogs::class . '@findAll');
    Route::get('/{id}', Blogs::class . '@findOne');
    Route::patch('/{id}', Blogs::class . '@update');
    Route::delete('/{id}', Blogs::class . '@delete');
});

// ----------------------------------------------------------------
//                          Comments Routes
// ----------------------------------------------------------------
Route::middleware('auth:sanctum')->prefix('comments')->group(function () {
    Route::post('/', Comments::class . '@create');
    Route::get('/blog/{blog_id}', Comments::class . '@findAll');
    Route::get('/{id}', Comments::class . '@findOne');
    Route::patch('/{id}', Comments::class . '@update');
    Route::delete('/{id}', Comments::class . '@delete');
});
