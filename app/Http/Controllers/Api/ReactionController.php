<?php

namespace App\Http\Controllers\Api;

use App\Enums\ReactionType;
use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReactionController extends Controller
{
    public function store(Request $request, Guide $guide)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::enum(ReactionType::class)],
        ]);

        $reaction = Reaction::updateOrCreate(
            [
                'guide_id' => $guide->id,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
            ],
            [
                'guide_id' => $guide->id,
                'user_id' => Auth::id(),
                'type' => $validated['type'],
            ]
        );

        return response()->json([
            'success' => true,
            'reactions' => $this->getReactionCounts($guide),
            'userReactions' => $this->getUserReactions($guide),
        ]);
    }

    public function destroy(Request $request, Guide $guide)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::enum(ReactionType::class)],
        ]);

        Reaction::where([
            'guide_id' => $guide->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
        ])->delete();

        return response()->json([
            'success' => true,
            'reactions' => $this->getReactionCounts($guide),
            'userReactions' => $this->getUserReactions($guide),
        ]);
    }

    private function getReactionCounts(Guide $guide): array
    {
        $counts = Reaction::where('guide_id', $guide->id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Ensure all reaction types are present
        foreach (ReactionType::cases() as $type) {
            if (! isset($counts[$type->value])) {
                $counts[$type->value] = 0;
            }
        }

        return $counts;
    }

    private function getUserReactions(Guide $guide): array
    {
        if (! Auth::check()) {
            return [];
        }

        return Reaction::where([
            'guide_id' => $guide->id,
            'user_id' => Auth::id(),
        ])
            ->pluck('type')
            ->map(fn ($type) => $type->value)
            ->toArray();
    }
}
