<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use App\Models\RtfmMessage;
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

    public function show(string $slug)
    {
        $guide = Guide::with(['user', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $guide->increment('view_count');

        // Get random RTFM message
        $rtfmMessage = RtfmMessage::where('is_approved', true)
            ->where(function ($query) {
                // Match NSFW/SFW based on guide or user preference
                $isNsfw = auth()->check() && auth()->user()->preferences['mode'] === 'nsfw';
                $query->where('is_nsfw', $isNsfw);
            })
            ->inRandomOrder()
            ->first();

        // Get related guides
        $relatedGuides = Guide::where('status', 'published')
            ->where('visibility', 'public')
            ->where('category_id', $guide->category_id)
            ->where('id', '!=', $guide->id)
            ->limit(3)
            ->get();

        return Inertia::render('Guides/Show', [
            'guide' => $guide,
            'rtfmMessage' => $rtfmMessage?->message ?? "You should've RTFM... but we did it for you.",
            'relatedGuides' => $relatedGuides,
        ]);
    }
}
