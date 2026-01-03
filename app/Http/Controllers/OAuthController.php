<?php

namespace App\Http\Controllers;

use App\Actions\Auth\HandleOAuthCallback;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth callback
     */
    public function callback(string $provider, HandleOAuthCallback $handleCallback): RedirectResponse
    {
        $this->validateProvider($provider);

        try {
            $oauthUser = Socialite::driver($provider)->user();

            $user = $handleCallback($provider, $oauthUser);

            auth()->login($user, true);

            return redirect()->intended(config('fortify.home'));
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'oauth' => 'Unable to authenticate with '.$provider.'. Please try again.',
            ]);
        }
    }

    /**
     * Validate the OAuth provider
     */
    protected function validateProvider(string $provider): void
    {
        if (! in_array($provider, ['github', 'google'])) {
            abort(404);
        }
    }
}
