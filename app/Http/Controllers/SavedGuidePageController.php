<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SavedGuidePageController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $savedGuides = $user->savedGuides()
            ->with(['guide.category', 'guide.user'])
            ->latest()
            ->paginate(12);

        // Transform to include both SFW and NSFW TLDRs
        $savedGuides->getCollection()->transform(function ($savedGuide) {
            return [
                'id' => $savedGuide->id,
                'saved_at' => $savedGuide->created_at,
                'guide' => [
                    'id' => $savedGuide->guide->id,
                    'slug' => $savedGuide->guide->slug,
                    'title' => $savedGuide->guide->title,
                    'tldr' => $savedGuide->guide->tldr,
                    'tldr_nsfw' => $savedGuide->guide->tldr_nsfw ?? $savedGuide->guide->tldr,
                    'difficulty' => $savedGuide->guide->difficulty,
                    'category' => $savedGuide->guide->category->name,
                    'view_count' => $savedGuide->guide->view_count,
                ],
            ];
        });

        return Inertia::render('SavedGuides/Index', [
            'savedGuides' => $savedGuides,
        ]);
    }
}
