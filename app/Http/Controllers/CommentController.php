<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'text' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);


        $post = Post::findOrFail($data['post_id']);
        abort_unless($post->isVisibleTo($request->user()), 404);

        if (! empty($data['parent_id'])) {
            abort_unless(
                Comment::where('id', $data['parent_id'])->where('post_id', $post->id)->exists(),
                422,
                'Invalid parent comment.'
            );
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $data['parent_id'] ?? null,
            'author' => Auth::user()->name,
            'text' => $data['text'],
        ]);
        Cache::forget("posts:show:{$post->id}");

        $comment->load('user');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'text' => $comment->text,
                    'author' => $comment->user?->name ?? $comment->author,
                    'user_id' => $comment->user_id,
                    'parent_id' => $comment->parent_id ?? null,
                    'avatar' => $comment->user?->avatar ?: asset('images/default-avatar.svg'),
                    'profile_url' => $comment->user ? route('users.show', $comment->user) : null,
                    'created_at' => $comment->created_at->diffForHumans(),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth::id() !== $comment->user_id && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $postId = $comment->post_id;
        $comment->delete();

        Cache::forget("posts:show:{$postId}");

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment_id' => (int) $id,
            ]);
        }

        return back()->with('success', 'Comment deleted successfully.');
    }
}
