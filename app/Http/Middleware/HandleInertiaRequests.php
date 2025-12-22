<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function rootView(Request $request): string
    {
        return 'app';
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app' => [
                'name' => config('app.name', 'RTFM.guide'),
            ],
            'mode' => $request->session()->get('content_mode', 'sfw'),
            'flash' => [
                'subscribed' => fn () => $request->session()->get('subscribed', false),
                'success' => fn () => $request->session()->get('success'),
            ],
        ]);
    }
}
