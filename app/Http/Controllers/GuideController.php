<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
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
        $guide = Guide::with(['user', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $guide->increment('view_count');

        // Determine user's preferred mode
        $isNsfw = auth()->check() && auth()->user()->preferences['mode'] === 'nsfw';

        // Get random RTFM message
        $rtfmMessage = RtfmMessage::where('is_approved', true)
            ->where('is_nsfw', $isNsfw)
            ->inRandomOrder()
            ->first();

        // Get related guides
        $relatedGuides = Guide::where('status', 'published')
            ->where('visibility', 'public')
            ->where('category_id', $guide->category_id)
            ->where('id', '!=', $guide->id)
            ->limit(3)
            ->get();

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
            ],
            'content' => $content,
            'defaultMode' => $isNsfw ? 'nsfw' : 'sfw',
            'rtfmMessage' => $rtfmMessage?->message ?? "You should've RTFM... but we did it for you.",
            'relatedGuides' => $relatedGuides,
        ]);
    }
}
