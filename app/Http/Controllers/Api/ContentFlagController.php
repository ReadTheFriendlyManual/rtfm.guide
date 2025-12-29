<?php

namespace App\Http\Controllers\Api;

use App\Enums\FlagReason;
use App\Enums\FlagStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\ContentFlag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class ContentFlagController extends Controller
{
    public function flagComment(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'reason' => ['required', new Enum(FlagReason::class)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        // Prevent users from flagging their own comments
        if ($comment->user_id === Auth::id()) {
            return response()->json([
                'message' => 'You cannot flag your own comment.',
            ], 422);
        }

        // Check if user already flagged this comment
        $existingFlag = ContentFlag::where([
            'user_id' => Auth::id(),
            'flaggable_id' => $comment->id,
            'flaggable_type' => Comment::class,
        ])->first();

        if ($existingFlag) {
            return response()->json([
                'message' => 'You have already flagged this comment.',
            ], 422);
        }

        $flag = ContentFlag::create([
            'user_id' => Auth::id(),
            'flaggable_id' => $comment->id,
            'flaggable_type' => Comment::class,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment has been flagged for review.',
            'flag' => [
                'id' => $flag->id,
                'reason' => $flag->reason->value,
            ],
        ], 201);
    }

    public function unflagComment(Comment $comment)
    {
        $flag = ContentFlag::where([
            'user_id' => Auth::id(),
            'flaggable_id' => $comment->id,
            'flaggable_type' => Comment::class,
        ])->first();

        if (! $flag) {
            return response()->json([
                'message' => 'You have not flagged this comment.',
            ], 404);
        }

        // Only allow removing flag if it hasn't been reviewed yet
        if ($flag->status !== FlagStatus::Pending) {
            return response()->json([
                'message' => 'Cannot remove flag that has already been reviewed.',
            ], 422);
        }

        $flag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flag removed successfully.',
        ]);
    }
}
