<?php

use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it('generates valid verification token that can be verified', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    // Generate verification token
    $verificationToken = EmailVerificationToken::generate($user);

    // Visit the verification URL
    $response = $this->get(route('verification.verify', ['token' => $verificationToken->token]));

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success', 'Your email has been verified successfully!');

    // Verify user is now verified
    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeTrue();

    // Token should be deleted after use
    expect(EmailVerificationToken::where('token', $verificationToken->token)->exists())->toBeFalse();
});

it('sends verification email with valid token', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    // Send verification notification
    $user->sendEmailVerificationNotification();

    // Assert notification was sent
    Notification::assertSentTo(
        $user,
        \App\Notifications\VerifyEmailNotification::class,
        function ($notification, $channels) use ($user) {
            // Get the verification URL from the notification
            $mail = $notification->toMail($user);
            $url = $mail->viewData['url'];

            // Verify URL structure
            expect($url)->toContain('https://');
            expect($url)->toContain('/email/verify/');

            // Verify token exists in database
            expect(EmailVerificationToken::where('user_id', $user->id)->exists())->toBeTrue();

            return true;
        }
    );
});

it('rejects expired verification tokens', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    // Create an expired token
    $token = EmailVerificationToken::create([
        'user_id' => $user->id,
        'token' => \Illuminate\Support\Str::random(64),
        'expires_at' => now()->subHours(1),
    ]);

    // Try to verify with expired token
    $response = $this->get(route('verification.verify', ['token' => $token->token]));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Verification link has expired. Please request a new one.');

    // User should still be unverified
    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeFalse();

    // Token should be deleted
    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeFalse();
});

it('rejects invalid verification tokens', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    // Try to verify with invalid token
    $response = $this->get(route('verification.verify', ['token' => 'invalid-token']));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Invalid verification link.');

    // User should still be unverified
    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeFalse();
});

it('handles already verified users gracefully', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    // Generate token for already verified user
    $token = EmailVerificationToken::generate($user);

    // Try to verify
    $response = $this->get(route('verification.verify', ['token' => $token->token]));

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('info', 'Email already verified.');

    // Token should be deleted
    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeFalse();
});
