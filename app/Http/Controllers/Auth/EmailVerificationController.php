<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use App\Support\Toast;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Verify email using token.
     */
    public function __invoke(Request $request, string $token): RedirectResponse
    {
        $verificationToken = EmailVerificationToken::where('token', $token)
            ->with('user')
            ->first();

        if (! $verificationToken) {
            Toast::error('Invalid verification link.');

            return redirect()->route('login');
        }

        if ($verificationToken->isExpired()) {
            $verificationToken->delete();
            Toast::error('Verification link has expired. Please request a new one.');

            return redirect()->route('login');
        }

        $user = $verificationToken->user;

        if ($user->hasVerifiedEmail()) {
            $verificationToken->delete();
            Toast::info('Email already verified.');

            return redirect()->route('login');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $verificationToken->delete();

        Toast::success('Your email has been verified successfully! Please log in.');

        return redirect()->route('login');
    }
}
