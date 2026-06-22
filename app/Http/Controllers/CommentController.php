<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
    }

    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'text' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'author' => Auth::user()->name,
            'text' => $request->text,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function postComment(string $id)
    {
    }

    public function commentInt(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment_id' => (int) $id,
            ]);
        }

        return back()->with('success', 'Comment deleted successfully.');
    }
}
