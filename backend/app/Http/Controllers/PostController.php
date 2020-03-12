<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Topic;
use App\Http\Resources\Post as PostResource;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    public function show(Topic $topic, Post $post)
    {
        return new PostResource($post);
    }

    public function store(PostStoreRequest $request, Topic $topic)
    {
        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->posts()->save($post);

        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, Topic $topic, Post $post)
    {
        $this->authorize('update', $post);
        $post->body = $request->body;
        $post->save();

        return new PostResource($post);
    }

    public function destroy(Topic $topic, Post $post)
    {
        $this->authorize('destroy', $post);
        $post->delete();

        return response(null, 204);
    }
}
