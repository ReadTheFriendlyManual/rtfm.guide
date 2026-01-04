<?php

namespace App\Http\Controllers;

use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Category;
use App\Models\Guide;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * Reserved route slugs that should not be used as category slugs
     */
    private const RESERVED_SLUGS = [
        'dashboard',
        'guides',
        'settings',
        'profile',
        'search',
        'api',
        'nova',
        'login',
        'register',
        'logout',
        'password',
        'verify-email',
        'my-guides',
        'saved-guides',
        'revisions',
        'share',
    ];

    public function show(string $slug)
    {
        // Check for reserved slug conflicts
        if (in_array($slug, self::RESERVED_SLUGS)) {
            throw new NotFoundHttpException();
        }

        $category = Category::with('flags')->where('slug', $slug)->firstOrFail();

        // Query featured guides using the guides relationship with filters
        $featuredGuides = Guide::where('category_id', $category->id)
            ->featured()
            ->where('status', GuideStatus::Published)
            ->where('visibility', GuideVisibility::Public)
            ->with(['user', 'category'])
            ->latest('published_at')
            ->limit(6)
            ->get()
            ->map(function ($guide) {
                return [
                    ...$guide->toArray(),
                    'tldr_sfw' => $guide->tldr,
                    'tldr_nsfw' => $guide->tldr_nsfw ?? $guide->tldr,
                ];
            });

        // Load featured writers with selected fields
        $featuredWriters = $category->featuredWriters()
            ->select([
                'users.id',
                'users.name',
                'users.avatar',
                'users.bio',
                'users.featured_bio',
                'users.github_username',
                'users.gitlab_username',
                'users.twitter_username',
                'users.linkedin_username',
                'users.website_url',
            ])
            ->get();

        return Inertia::render('Categories/Show', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'icon' => $category->icon,
                'flags' => $category->flags,
            ],
            'featuredGuides' => $featuredGuides,
            'featuredWriters' => $featuredWriters,
        ]);
    }
}
