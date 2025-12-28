<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PreferencesController extends Controller
{
    public function updateMode(Request $request): Response
    {
        $validated = $request->validate([
            'mode' => ['required', Rule::in(['sfw', 'nsfw'])],
        ]);

        $request->user()->update([
            'preferred_rtfm_mode' => $validated['mode'],
        ]);

        return response()->noContent();
    }

    public function updateTheme(Request $request): Response
    {
        $validated = $request->validate([
            'theme' => ['required', Rule::in(['light', 'dark'])],
        ]);

        $request->user()->update([
            'preferred_theme' => $validated['theme'],
        ]);

        return response()->noContent();
    }
}
