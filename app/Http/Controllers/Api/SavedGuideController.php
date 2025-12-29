<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavedGuide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavedGuideController extends Controller
{
    public function store(Request $request, int $guideId): JsonResponse
    {
        $request->validate([
            'guide_id' => 'sometimes|exists:guides,id',
        ]);

        $user = $request->user();

        // Check if already saved
        if ($user->hasSavedGuide($guideId)) {
            return response()->json([
                'message' => 'Guide already saved',
                'saved' => true,
            ]);
        }

        SavedGuide::create([
            'user_id' => $user->id,
            'guide_id' => $guideId,
        ]);

        return response()->json([
            'message' => 'Guide saved successfully',
            'saved' => true,
        ]);
    }

    public function destroy(Request $request, int $guideId): JsonResponse
    {
        $user = $request->user();

        SavedGuide::where('user_id', $user->id)
            ->where('guide_id', $guideId)
            ->delete();

        return response()->json([
            'message' => 'Guide removed from saved',
            'saved' => false,
        ]);
    }
}
