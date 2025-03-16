<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // app()->setLocale('uz');
        $posts = Post::with('translations')->get();
        return response()->json(PostResource::collection($posts));
    }
    public function show($id)
    {
        $post = Post::with('translations')->findOrFail($id);
        // $post->deleteTranslations(['en', 'de']);
        return response()->json($post);
    }
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        $translationColumns = ['title', 'content'];
        $translations = $this->prepareTranslations($request->translations, $translationColumns);
        $post->setTranslations($translations);
        return response()->json($post->load('translations'), 201);
    }
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        $translationColumns = ['title', 'content'];
        $translations = $this->prepareTranslations($request->translations, $translationColumns);
        $post->setTranslations($translations);
        return response()->json($post->load('translations'), 200);
    }
}
