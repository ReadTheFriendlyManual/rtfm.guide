<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\User;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        // Get user's published guides
        $guides = Guide::where('user_id', $user->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        // Calculate stats
        $stats = [
            'total_guides' => Guide::where('user_id', $user->id)
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->count(),
            'total_views' => Guide::where('user_id', $user->id)
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->sum('view_count'),
            'member_since' => $user->created_at->format('F Y'),
        ];

        return Inertia::render('Users/Profile', [
            'profileUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'guides' => $guides->through(fn ($guide) => [
                'id' => $guide->id,
                'slug' => $guide->slug,
                'title' => $guide->title,
                'tldr' => $guide->tldr,
                'difficulty' => $guide->difficulty->value,
                'category' => [
                    'name' => $guide->category->name,
                    'slug' => $guide->category->slug,
                ],
                'view_count' => $guide->view_count,
                'published_at' => $guide->published_at->diffForHumans(),
            ]),
            'stats' => $stats,
        ]);
    }
}
