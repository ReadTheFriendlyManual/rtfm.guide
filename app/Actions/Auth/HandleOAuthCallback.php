<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class HandleOAuthCallback
{
    /**
     * Handle OAuth callback and create or update user
     */
    public function __invoke(string $provider, SocialiteUser $oauthUser): User
    {
        // Check if user exists with this OAuth provider and ID
        $user = User::query()
            ->where('oauth_provider', $provider)
            ->where('oauth_id', $oauthUser->getId())
            ->first();

        if ($user) {
            // Update existing OAuth user's information
            $this->updateUserFromOAuth($user, $oauthUser);

            return $user;
        }

        // Check if user exists with this email
        $user = User::query()
            ->where('email', $oauthUser->getEmail())
            ->first();

        if ($user) {
            // Link OAuth account to existing user
            $this->linkOAuthToUser($user, $provider, $oauthUser);

            return $user;
        }

        // Create new user with OAuth data
        return $this->createUserFromOAuth($provider, $oauthUser);
    }

    /**
     * Update user information from OAuth data
     */
    protected function updateUserFromOAuth(User $user, SocialiteUser $oauthUser): void
    {
        $user->update([
            'avatar' => $oauthUser->getAvatar(),
        ]);
    }

    /**
     * Link OAuth account to existing user
     */
    protected function linkOAuthToUser(User $user, string $provider, SocialiteUser $oauthUser): void
    {
        $user->update([
            'oauth_provider' => $provider,
            'oauth_id' => $oauthUser->getId(),
            'avatar' => $user->avatar ?? $oauthUser->getAvatar(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);
    }

    /**
     * Create new user from OAuth data
     */
    protected function createUserFromOAuth(string $provider, SocialiteUser $oauthUser): User
    {
        return User::create([
            'name' => $oauthUser->getName() ?? $oauthUser->getNickname() ?? 'User',
            'email' => $oauthUser->getEmail(),
            'oauth_provider' => $provider,
            'oauth_id' => $oauthUser->getId(),
            'avatar' => $oauthUser->getAvatar(),
            'email_verified_at' => now(), // OAuth users are auto-verified
            'password' => Hash::make(Str::random(32)), // Random password for OAuth users
        ]);
    }
}
