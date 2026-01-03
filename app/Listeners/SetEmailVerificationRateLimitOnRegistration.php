<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;

class SetEmailVerificationRateLimitOnRegistration
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Hit rate limiter for initial verification email sent during registration
        $key = 'email-verification:'.$event->user->id;
        RateLimiter::hit($key, 60);
    }
}
