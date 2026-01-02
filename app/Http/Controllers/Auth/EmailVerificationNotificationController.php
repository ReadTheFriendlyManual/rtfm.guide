<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $key = 'email-verification:'.$request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);

            Toast::error("Please wait {$seconds} seconds before requesting another verification email.");

            return back();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        RateLimiter::hit($key, 60);

        Toast::info('A new verification link has been sent to your email address.');

        return back();
    }
}
