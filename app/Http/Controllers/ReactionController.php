<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    private function ensureCommentIsVisible(Request $request, Comment $comment): void
    {
        abort_unless(
            $comment->post && $comment->post->isVisibleTo($request->user()),
            404
        );
    }

    public function toggle(Request $request, Comment $comment)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in.'], 401);
        }

        $this->ensureCommentIsVisible($request, $comment);

        $request->validate([
            'reaction' => 'nullable|string|in:like,dislike,heart,laugh',
        ]);

        $user = auth()->user();
        $newReactionType = $request->input('reaction', 'like');

        $existingReaction = $comment->reactions()->where('user_id', $user->id)->first();

        if ($existingReaction && $existingReaction->reaction === $newReactionType) {
            $existingReaction->delete();

            return response()->json([
                'message' => 'Reaction removed.',
                'action' => 'deleted',
            ]);
        }

        $comment->reactions()->updateOrCreate(
            ['user_id' => $user->id],
            ['reaction' => $newReactionType]
        );

        return response()->json([
            'message' => $existingReaction ? 'Reaction updated.' : 'Reaction added.',
            'action' => $existingReaction ? 'updated' : 'added',
            'reaction' => $newReactionType,
        ]);
    }

    public function remove(Request $request, Comment $comment)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in.'], 401);
        }

        $this->ensureCommentIsVisible($request, $comment);

        $deleted = $comment->reactions()->where('user_id', auth()->id())->delete();

        return $deleted
            ? response()->json(['message' => 'Reaction removed.'])
            : response()->json(['error' => 'No reaction to remove.'], 404);
    }
}
