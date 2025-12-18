<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuideFilterRequest;
use App\Http\Resources\GuideResource;
use App\Models\Guide;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuideController extends Controller
{
    public function index(GuideFilterRequest $request): AnonymousResourceCollection
    {
        $filters = $request->filters();

        $guides = Guide::query()
            ->with(['category', 'author'])
            ->withCount([
                'savedBy as saved_by_count',
                'reactions as helpful_reactions_count' => fn ($query) => $query->where('type', 'helpful'),
                'reactions as saved_me_reactions_count' => fn ($query) => $query->where('type', 'saved_me'),
                'reactions as outdated_reactions_count' => fn ($query) => $query->where('type', 'outdated'),
                'reactions as needs_update_reactions_count' => fn ($query) => $query->where('type', 'needs_update'),
            ])
            ->published()
            ->search($filters['search'])
            ->forDifficulty($filters['difficulty'])
            ->forCategory($request->resolvedCategory())
            ->when($filters['os'], fn ($query) => $query->whereJsonContains('os_tags', $filters['os']))
            ->latest('published_at')
            ->paginate(perPage: $filters['per_page']);

        return GuideResource::collection($guides);
    }

    public function show(Guide $guide): GuideResource
    {
        abort_if($guide->status !== 'published' || $guide->visibility !== 'public', 404);

        return GuideResource::make($guide->load(['category', 'author'])
            ->loadCount([
                'savedBy as saved_by_count',
                'reactions as helpful_reactions_count' => fn ($query) => $query->where('type', 'helpful'),
                'reactions as saved_me_reactions_count' => fn ($query) => $query->where('type', 'saved_me'),
                'reactions as outdated_reactions_count' => fn ($query) => $query->where('type', 'outdated'),
                'reactions as needs_update_reactions_count' => fn ($query) => $query->where('type', 'needs_update'),
            ]));
    }

    public function trending(): AnonymousResourceCollection
    {
        $guides = Guide::query()
            ->with(['category', 'author'])
            ->withCount([
                'savedBy as saved_by_count',
                'reactions as helpful_reactions_count' => fn ($query) => $query->where('type', 'helpful'),
                'reactions as saved_me_reactions_count' => fn ($query) => $query->where('type', 'saved_me'),
                'reactions as outdated_reactions_count' => fn ($query) => $query->where('type', 'outdated'),
                'reactions as needs_update_reactions_count' => fn ($query) => $query->where('type', 'needs_update'),
            ])
            ->published()
            ->trending()
            ->limit(5)
            ->get();

        return GuideResource::collection($guides);
    }

    public function random(): GuideResource
    {
        $guide = Guide::query()
            ->with(['category', 'author'])
            ->withCount([
                'savedBy as saved_by_count',
                'reactions as helpful_reactions_count' => fn ($query) => $query->where('type', 'helpful'),
                'reactions as saved_me_reactions_count' => fn ($query) => $query->where('type', 'saved_me'),
                'reactions as outdated_reactions_count' => fn ($query) => $query->where('type', 'outdated'),
                'reactions as needs_update_reactions_count' => fn ($query) => $query->where('type', 'needs_update'),
            ])
            ->published()
            ->inRandomOrder()
            ->first();

        abort_if($guide === null, 404);

        return GuideResource::make($guide);
    }
}
