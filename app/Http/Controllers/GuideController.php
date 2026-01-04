<?php

namespace App\Http\Controllers;

use App\Enums\ReactionType;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Guide;
use App\Models\Reaction;
use App\Models\RtfmMessage;
use App\Services\MarkdownRenderer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $query = Guide::with(['user', 'category'])
            ->where('status', 'published')
            ->where('visibility', 'public');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by OS
        if ($request->filled('os')) {
            $query->whereJsonContains('os_tags', $request->os);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        match ($sortBy) {
            'popular' => $query->orderBy('view_count', 'desc'),
            'trending' => $query->where('created_at', '>=', now()->subWeek())
                ->orderBy('view_count', 'desc'),
            default => $query->latest('published_at'),
        };

        $guides = $query->paginate(12)->withQueryString();

        // Transform guides to include both SFW and NSFW TLDRs
        $guides->getCollection()->transform(function ($guide) {
            return [
                ...$guide->toArray(),
                'tldr_sfw' => $guide->tldr,
                'tldr_nsfw' => $guide->tldr_nsfw ?? $guide->tldr,
            ];
        });

        // Get filter options
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();

        return Inertia::render('Guides/Index', [
            'guides' => $guides,
            'categories' => $categories,
            'filters' => [
                'category' => $request->category,
                'difficulty' => $request->difficulty,
                'os' => $request->os,
                'sort' => $sortBy,
            ],
        ]);
    }

    public function show(string $slug, MarkdownRenderer $markdown)
    {
        $guide = Guide::with(['user', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $guide->increment('view_count');

        // Get random RTFM message based on user's preferred mode
        // Check cookie first (for guests), then user preference (for authenticated users)
        $isNsfw = request()->cookie('rtfm_mode') === 'nsfw'
            || (auth()->check() && auth()->user()->preferred_rtfm_mode === 'nsfw');

        $rtfmMessage = RtfmMessage::where('is_approved', true)
            ->where('is_nsfw', $isNsfw)
            ->inRandomOrder()
            ->first();

        // Get related guides with improved algorithm
        // Priority: 1. Same category & difficulty, 2. Same category, 3. Similar OS tags
        $relatedGuides = collect();

        // First: Try to find guides in the same category with same difficulty
        $sameCategoryDifficulty = Guide::where('status', 'published')
            ->where('visibility', 'public')
            ->where('category_id', $guide->category_id)
            ->where('difficulty', $guide->difficulty)
            ->where('id', '!=', $guide->id)
            ->orderBy('view_count', 'desc')
            ->limit(3)
            ->get();

        $relatedGuides = $relatedGuides->merge($sameCategoryDifficulty);

        // If we need more, get from same category regardless of difficulty
        if ($relatedGuides->count() < 3) {
            $sameCategory = Guide::where('status', 'published')
                ->where('visibility', 'public')
                ->where('category_id', $guide->category_id)
                ->where('id', '!=', $guide->id)
                ->whereNotIn('id', $relatedGuides->pluck('id'))
                ->orderBy('view_count', 'desc')
                ->limit(3 - $relatedGuides->count())
                ->get();

            $relatedGuides = $relatedGuides->merge($sameCategory);
        }

        // If still need more, search by similar OS tags
        if ($relatedGuides->count() < 3 && $guide->os_tags) {
            $similarOsTags = Guide::where('status', 'published')
                ->where('visibility', 'public')
                ->where('id', '!=', $guide->id)
                ->whereNotIn('id', $relatedGuides->pluck('id'))
                ->where(function ($query) use ($guide) {
                    foreach ($guide->os_tags as $tag) {
                        $query->orWhereJsonContains('os_tags', $tag);
                    }
                })
                ->orderBy('view_count', 'desc')
                ->limit(3 - $relatedGuides->count())
                ->get();

            $relatedGuides = $relatedGuides->merge($similarOsTags);
        }

        // Render markdown content on the backend
        $content = [
            'sfw' => [
                'tldr' => $guide->tldr,
                'html' => $markdown->render($guide->content),
            ],
            'nsfw' => [
                'tldr' => $guide->tldr_nsfw ?? $guide->tldr,
                'html' => $markdown->render($guide->content_nsfw ?? $guide->content),
            ],
        ];

        // Get reaction counts
        $reactionCounts = Reaction::where('guide_id', $guide->id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Ensure all reaction types are present
        foreach (ReactionType::cases() as $type) {
            if (! isset($reactionCounts[$type->value])) {
                $reactionCounts[$type->value] = 0;
            }
        }

        // Get user's reactions
        $userReactions = [];
        if (auth()->check()) {
            $userReactions = Reaction::where([
                'guide_id' => $guide->id,
                'user_id' => auth()->id(),
            ])
                ->pluck('type')
                ->map(fn ($type) => $type->value)
                ->toArray();
        }

        // Get top-level comments (parent comments only) with replies
        // Show approved comments OR user's own pending comments
        $comments = Comment::where('guide_id', $guide->id)
            ->whereNull('parent_id')
            ->where(function ($query) {
                $query->where('is_approved', true)
                    ->when(auth()->check(), function ($q) {
                        $q->orWhere('user_id', auth()->id());
                    });
            })
            ->with(['user', 'replies.user'])
            ->latest()
            ->get()
            ->map(fn ($comment) => $this->formatComment($comment))
            ->toArray();

        return Inertia::render('Guides/Show', [
            'guide' => [
                'id' => $guide->id,
                'slug' => $guide->slug,
                'title' => $guide->title,
                'difficulty' => $guide->difficulty,
                'estimated_minutes' => $guide->estimated_minutes,
                'os_tags' => $guide->os_tags,
                'view_count' => $guide->view_count,
                'published_at' => $guide->published_at,
                'user' => $guide->user,
                'category' => $guide->category,
                'is_saved' => auth()->check() && auth()->user()->hasSavedGuide($guide->id),
                'has_pending_revision' => $guide->hasPendingRevision(),
                'flags' => $guide->flags,
            ],
            'content' => $content,
            'rtfmMessage' => $rtfmMessage?->message ?? "You should've RTFM... but we did it for you.",
            'relatedGuides' => $relatedGuides,
            'reactions' => $reactionCounts,
            'userReactions' => $userReactions,
            'comments' => $comments,
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
            'created_at' => $comment->created_at->diffForHumans(),
            'updated_at' => $comment->updated_at->diffForHumans(),
            'can_edit' => auth()->check() && auth()->id() === $comment->user_id,
            'can_delete' => auth()->check() && auth()->id() === $comment->user_id,
            'replies' => $comment->replies
                ->filter(function ($reply) {
                    // Show approved replies OR user's own pending replies
                    return $reply->is_approved || (auth()->check() && $reply->user_id === auth()->id());
                })
                ->map(fn ($reply) => $this->formatComment($reply))
                ->values()
                ->toArray(),
        ];
    }
}
