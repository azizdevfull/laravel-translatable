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
        // $post->deleteTranslations('de'); agar bitta tilni o'chirish kerak bo'lsa
        // $post->deleteTranslations(['de', 'en']); // agar bir nechta tilni o'chirish kerak bo'lsa
        return response()->json(new PostResource($post->refresh()));
    }
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        $translationColumns = ['title', 'content'];
        $translations = $this->prepareTranslations($request->translations, $translationColumns);
        $post->setTranslations($translations);
        return response()->json(new PostResource($post->load('translations')), 201);
    }
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        $translationColumns = ['title', 'content'];
        $translations = $this->prepareTranslations($request->translations, $translationColumns);
        $post->setTranslations($translations);
        return response()->json(new PostResource($post->load('translations')), 200);
    }
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(null, 204);
    }
}
