<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\ShareLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ShareLinkController extends Controller
{
    public function store(Guide $guide): JsonResponse
    {
        $shareLinks = [];

        foreach (['sfw', 'nsfw'] as $mode) {
            $existingLink = $guide->shareLinks()->where('mode', $mode)->first();

            if ($existingLink) {
                $shareLinks[$mode] = $existingLink;
            } else {
                $shareLinks[$mode] = $guide->shareLinks()->create([
                    'token' => ShareLink::generateToken(),
                    'mode' => $mode,
                ]);
            }
        }

        return response()->json([
            'sfw' => route('share.show', $shareLinks['sfw']->token),
            'nsfw' => route('share.show', $shareLinks['nsfw']->token),
        ]);
    }

    public function show(string $token): RedirectResponse
    {
        $shareLink = ShareLink::where('token', $token)->firstOrFail();

        $shareLink->incrementVisitCount();

        $guide = $shareLink->guide;

        $response = redirect()->route('guides.show', $guide);

        $existingMode = request()->cookie('rtfm_mode');

        if (! $existingMode) {
            $response->cookie('rtfm_mode', $shareLink->mode, 60 * 24 * 365);
        }

        return $response;
    }
}
