<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    private function viewer(Request $request)
    {
        return $request->user('sanctum');
    }

    private function canManage(Request $request, Comment $comment): bool
    {
        $user = $request->user();

        return $user && ($user->id === $comment->user_id || $user->isAdmin());
    }

    public function index(Request $request)
    {
        $viewer = $this->viewer($request);

        $comments = Comment::whereHas('post', fn($q) => $q->published()->visibleTo($viewer))
            ->latest()
            ->paginate(15);

        return CommentResource::collection($comments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'text' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        if (! $post->isVisibleTo($request->user())) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if (! empty($validated['parent_id'])
            && ! Comment::where('id', $validated['parent_id'])->where('post_id', $post->id)->exists()) {
            return response()->json(['message' => 'Invalid parent comment'], 422);
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => $request->user()->id,
            'author' => $request->user()->name,
            'text' => $validated['text'],
        ]);

        Cache::forget("posts:show:{$post->id}");

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    public function show(Request $request, string $id)
    {
        $comment = Comment::with('post')->find($id);

        if (! $comment || ! $comment->post || ! $comment->post->isVisibleTo($this->viewer($request))) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return new CommentResource($comment);
    }

    public function update(Request $request, string $id)
    {
        $comment = Comment::find($id);
        if (! $comment) {
            return response()->json(['message' => 'Comment is not found'], 404);
        }

        if (! $this->canManage($request, $comment)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        Cache::forget("posts:show:{$comment->post_id}");

        return new CommentResource($comment);
    }

    public function destroy(Request $request, string $id)
    {
        $comment = Comment::find($id);

        if (! $comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if (! $this->canManage($request, $comment)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $postId = $comment->post_id;

        $comment->delete();

        Cache::forget("posts:show:{$postId}");

        return response()->noContent();
    }
}
