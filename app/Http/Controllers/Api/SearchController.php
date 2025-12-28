<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function quick(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [],
            ]);
        }

        // Perform search using Scout + Meilisearch
        $results = Guide::search($query)
            ->query(fn ($meilisearch) => $meilisearch->limit(5))
            ->get()
            ->map(fn ($guide) => [
                'id' => $guide->id,
                'slug' => $guide->slug,
                'title' => $guide->title,
                'tldr' => $guide->tldr,
                'category' => $guide->category->name,
                'difficulty' => $guide->difficulty,
            ]);

        return response()->json([
            'results' => $results,
        ]);
    }
}
