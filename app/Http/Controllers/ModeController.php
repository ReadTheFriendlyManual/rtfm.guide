<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModeController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mode' => ['required', 'in:sfw,nsfw'],
        ]);

        $request->session()->put('content_mode', $validated['mode']);

        return back();
    }
}
