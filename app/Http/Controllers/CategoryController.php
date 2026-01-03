<?php

namespace App\Http\Controllers;

use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with([
                'featuredGuides' => function ($query) {
                    $query->where('status', GuideStatus::Published)
                        ->where('visibility', GuideVisibility::Public)
                        ->with(['user', 'category'])
                        ->latest('published_at')
                        ->limit(6);
                },
                'featuredWriters' => function ($query) {
                    $query->select([
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
                    ]);
                },
            ])
            ->firstOrFail();

        // Transform guides to include both SFW and NSFW TLDRs
        $featuredGuides = $category->featuredGuides->map(function ($guide) {
            return [
                ...$guide->toArray(),
                'tldr_sfw' => $guide->tldr,
                'tldr_nsfw' => $guide->tldr_nsfw ?? $guide->tldr,
            ];
        });

        return Inertia::render('Categories/Show', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'icon' => $category->icon,
            ],
            'featuredGuides' => $featuredGuides,
            'featuredWriters' => $category->featuredWriters,
        ]);
    }
}
