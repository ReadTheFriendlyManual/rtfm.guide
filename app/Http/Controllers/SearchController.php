<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guide;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [];

        // Build Meilisearch filters (string values must be quoted)
        if ($request->filled('difficulty')) {
            $filters[] = "difficulty = '{$request->difficulty}'";
        }

        if ($request->filled('category')) {
            $filters[] = "category_slug = '{$request->category}'";
        }

        // Perform search using Scout + Meilisearch
        $results = collect();
        if (! empty($query)) {
            $searchQuery = Guide::search($query);

            // Apply filters using Meilisearch options
            if (! empty($filters)) {
                $searchQuery->options(['filter' => implode(' AND ', $filters)]);
            }

            // Get paginated results
            $results = $searchQuery
                ->query(fn ($meilisearch) => $meilisearch->limit(20))
                ->paginate(12)
                ->withQueryString();
        }

        // Get filter options
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();

        // Get popular guides if no search query
        $popularGuides = [];
        if (empty($query)) {
            $popularGuides = Guide::with(['category'])
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->orderBy('view_count', 'desc')
                ->limit(6)
                ->get()
                ->map(fn ($guide) => [
                    'id' => $guide->id,
                    'slug' => $guide->slug,
                    'title' => $guide->title,
                    'tldr' => $guide->tldr,
                    'category' => $guide->category->name,
                    'difficulty' => $guide->difficulty,
                    'view_count' => $guide->view_count,
                ]);
        }

        return Inertia::render('Search/Index', [
            'results' => $results,
            'query' => $query,
            'categories' => $categories,
            'popularGuides' => $popularGuides,
            'filters' => [
                'difficulty' => $request->difficulty,
                'category' => $request->category,
            ],
        ]);
    }
}
