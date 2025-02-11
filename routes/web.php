<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $post = Post::find(1);

    $post->translations()->createMany([
        ['locale' => 'en', 'title' => 'Laravel Tips', 'content' => 'Laravel is awesome!'],
        ['locale' => 'uz', 'title' => 'Laravel Maslahatlar', 'content' => 'Laravel juda zo‘r!'],
    ]);
    // Barcha tarjimalar
    foreach ($post->translations as $translation) {
        echo "{$translation->locale}: {$translation->title}\n";
    }
});
