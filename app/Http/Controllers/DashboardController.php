<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get user stats
        $stats = [
            'guides_created' => Guide::where('user_id', $user->id)->count(),
            'guides_saved' => $user->savedGuides()->count(),
            'total_views' => Guide::where('user_id', $user->id)->sum('view_count'),
            'reputation_points' => $user->reputation_points,
        ];

        // Get recent saved guides
        $recentSaved = $user->savedGuides()
            ->with(['guide.category'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn ($saved) => [
                'id' => $saved->guide->id,
                'slug' => $saved->guide->slug,
                'title' => $saved->guide->title,
                'category' => $saved->guide->category->name,
                'difficulty' => $saved->guide->difficulty,
            ]);

        // Get user's recent guides
        $myRecentGuides = Guide::where('user_id', $user->id)
            ->with('category')
            ->latest('published_at')
            ->limit(3)
            ->get()
            ->map(fn ($guide) => [
                'id' => $guide->id,
                'slug' => $guide->slug,
                'title' => $guide->title,
                'category' => $guide->category->name,
                'status' => $guide->status,
                'view_count' => $guide->view_count,
            ]);

        // Get trending guides for discovery
        $trending = Guide::where('status', 'published')
            ->where('visibility', 'public')
            ->where('created_at', '>=', now()->subWeek())
            ->orderBy('view_count', 'desc')
            ->limit(4)
            ->get()
            ->map(fn ($guide) => [
                'id' => $guide->id,
                'slug' => $guide->slug,
                'title' => $guide->title,
                'view_count' => $guide->view_count,
            ]);

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'recentSaved' => $recentSaved,
            'myRecentGuides' => $myRecentGuides,
            'trending' => $trending,
        ]);
    }
}
