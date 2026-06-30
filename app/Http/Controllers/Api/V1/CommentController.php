<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validated();
        Comment::create($validated);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }
        $comment = Comment::create($data);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::find($id);
        if (! $comment) {
            return response()->json(['message' => 'Comment not found', 404]);
        }

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);
        if (! $comment) {
            return response()->json(['message' => 'Comment is not found'], 404);
        }
        $validated = $request->validated();
        Comment::create($validated);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }
        $comment->update($data);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comment::find($id);

        if (! $comment) {
            return response()->json(['message' => 'comment is not found', 404]);
        }

        $comment->delete();

        return new CommentResource($comment);
    }
}
