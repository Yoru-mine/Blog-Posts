<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class ApiPostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::paginate(5));
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        Post::create($validated);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post = Post::create($data);

        return new PostResource($post);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    public function update(StorePostRequest $request, $id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validated = $request->validated();
        Post::create($validated);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post->update($data);

        return new PostResource($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
