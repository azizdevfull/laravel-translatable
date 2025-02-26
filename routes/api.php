<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/', function () {
    // "title" ustuni ichida "first" yoki "second" bo'lgan barcha yozuvlarni olish
    $posts = Post::with('translations')->whereTranslationLike('title', '%Aziz%')
        ->orWhereTranslationLike('title', '%second%')
        ->first();

    return response()->json($posts);
});
Route::apiResource('posts', PostController::class);