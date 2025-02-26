<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('translations')->get();

        return response()->json($posts);
    }
    public function show($id)
    {
        $post = Post::with('translations')->find($id);

        return response()->json($post);
    }
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        $post->setTranslations([
            'de' => [
                'title' => 'Mein erster bearbeiteter Beitrag',
                'content' => 'Dies ist der Inhalt meines ersten bearbeiteten Beitrags',
            ],
        ]);
        return response()->json($post->load('translations'), 201);
    }
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->all());
        $post->setTranslations([
            'en' => [
                'title' => 'My first edited post',
                'content' => 'This is my first edited post content',
            ],
            'de' => [
                'title' => 'Mein erster bearbeiteter Beitrag',
                'content' => 'Dies ist der Inhalt meines ersten bearbeiteten Beitrags',
            ],
            'uz' => [
                'title' => 'Mening birinchi tahrir qilingan postim',
                'content' => 'Bu mening birinchi tahrir qilingan postimning tarkibi',
            ],
        ]);
        return response()->json($post->load('translations'), 200);
    }
}
