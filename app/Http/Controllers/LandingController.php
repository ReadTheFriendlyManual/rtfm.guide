<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function show(Request $request): Response
    {
        $copy = trans('mode');

        $features = [
            ['icon' => '⚡️', 'title' => 'Blazing Fast Search', 'description' => 'Find what you need in milliseconds with Meilisearch tuned for dev docs.', 'color' => 'sky'],
            ['icon' => '👥', 'title' => 'Community Driven', 'description' => 'Submit guides, suggest edits, and earn reputation alongside your peers.', 'color' => 'indigo'],
            ['icon' => '📚', 'title' => 'Versioned Content', 'description' => 'Tailored answers for your stack: Laravel versions, OS releases, and more.', 'color' => 'rose'],
            ['icon' => '🏆', 'title' => 'Gamified Learning', 'description' => 'Badges, streaks, and leaderboards make mastery feel rewarding.', 'color' => 'amber'],
            ['icon' => '🔌', 'title' => 'API First', 'description' => 'Integrate RTFM into your tooling with a clean, well-documented REST API.', 'color' => 'emerald'],
            ['icon' => '🌍', 'title' => 'Multi-Language', 'description' => 'Guides and contributions in the language you work best with.', 'color' => 'violet'],
        ];

        $stats = [
            ['label' => 'Guides at Launch', 'value' => '50+', 'tone' => 'sky'],
            ['label' => 'Search Response Time', 'value' => '<150ms', 'tone' => 'indigo'],
            ['label' => 'Free & Open Source', 'value' => '100%', 'tone' => 'rose'],
        ];

        $guidePreview = [
            'title' => 'How to Restart Nginx',
            'path' => '/server/nginx/',
            'updated' => '3 days ago',
            'author' => 'Pete',
            'summary' => 'To gracefully restart Nginx without dropping connections, use sudo systemctl reload nginx.',
            'steps' => [
                'Ubuntu 22.04 server with sudo privileges',
                'Nginx installed and configured',
                'Access to terminal or SSH session',
            ],
            'commands' => [
                '# Check your config for syntax errors first!',
                'sudo nginx -t',
                '',
                '# If all is well, gracefully reload the service.',
                '# This finishes active connections before reloading.',
                'sudo systemctl reload nginx',
            ],
            'footer' => 'If you truly need to force a full restart (and can handle the downtime), swap reload for restart.',
        ];

        return Inertia::render('Home', [
            'mode' => $request->session()->get('content_mode', 'sfw'),
            'messages' => $copy,
            'features' => $features,
            'stats' => $stats,
            'guidePreview' => $guidePreview,
        ]);
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $request->session()->flash('subscribed', true);
        $request->session()->flash('success', "You're on the list! We'll be in touch.");

        return back()->withInput(['email' => $validated['email']]);
    }
}
