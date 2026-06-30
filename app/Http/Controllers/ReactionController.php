<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggle(Request $request, Comment $comment)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in.'], 401);
        }

        $user = auth()->user();
        $newReactionType = $request->input('reaction', 'like');

        $existingReaction = $comment->reactions()
            ->where('user_id', $user->id)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction === $newReactionType) {
                $existingReaction->delete();

                return response()->json([
                    'message' => 'Reaction removed.',
                    'action' => 'deleted',
                ]);
            }

            $existingReaction->update(['reaction' => $newReactionType]);

            return response()->json([
                'message' => 'Reaction updated.',
                'action' => 'updated',
            ]);
        }

        $comment->reactions()->create([
            'user_id' => $user->id,
            'reaction' => $newReactionType,
        ]);

        return response()->json([
            'message' => 'Reaction added.',
            'action' => 'added',
        ]);
    }

    public function remove(Request $request, Comment $comment)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'You must be logged in.'], 401);
        }

        $user = auth()->user();

        $existingReaction = $comment->reactions()
            ->where('user_id', $user->id)
            ->first();

        if ($existingReaction) {
            $existingReaction->delete();

            return response()->json(['message' => 'Reaction removed.']);
        }

        return response()->json(['error' => 'No reaction to remove.'], 404);
    }
}
