<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Guide $guide)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ]);

        // If parent_id is provided, ensure it belongs to the same guide
        if (isset($validated['parent_id']) && $validated['parent_id']) {
            $parentComment = Comment::findOrFail($validated['parent_id']);
            if ($parentComment->guide_id !== $guide->id) {
                return response()->json([
                    'message' => 'Invalid parent comment.',
                ], 422);
            }
        }

        // Auto-approve comments from users with previously approved comments
        $isApproved = Auth::user()->hasApprovedComment();

        $comment = Comment::create([
            'guide_id' => $guide->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'is_approved' => $isApproved,
        ]);

        $comment->load('user', 'replies.user');

        return response()->json([
            'success' => true,
            'comment' => $this->formatComment($comment),
        ], 201);
    }

    public function update(Request $request, Comment $comment)
    {
        // Ensure user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        $comment->load('user', 'replies.user');

        return response()->json([
            'success' => true,
            'comment' => $this->formatComment($comment),
        ]);
    }

    public function destroy(Comment $comment)
    {
        // Ensure user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }

    private function formatComment(Comment $comment): array
    {
        return [
            'id' => $comment->id,
            'guide_id' => $comment->guide_id,
            'parent_id' => $comment->parent_id,
            'content' => $comment->content,
            'is_approved' => $comment->is_approved,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
            ],
            'created_at' => $comment->created_at->toIso8601String(),
            'updated_at' => $comment->updated_at->toIso8601String(),
            'replies' => $comment->replies->map(fn ($reply) => $this->formatComment($reply))->toArray(),
        ];
    }
}
