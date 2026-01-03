<?php

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    RateLimiter::clear('email-verification:1');
});

it('sends verification notification to unverified user', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    $response = $this->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('info', 'A new verification link has been sent to your email address.');

    Notification::assertSentTo($user, \App\Notifications\VerifyEmailNotification::class);
});

it('redirects verified users to dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect('/dashboard');
});

it('rate limits verification notification requests', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    // First request should succeed
    $response = $this->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('info');

    // Second request should be rate limited
    $response = $this->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $response->assertSessionHasNoErrors();

    $errorMessage = session('error');
    expect($errorMessage)->toContain('Please wait');
    expect($errorMessage)->toContain('seconds');
});

it('allows request after rate limit expires', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $key = "email-verification:{$user->id}";

    // Manually set and expire the rate limiter
    RateLimiter::hit($key, 1);
    RateLimiter::clear($key);

    // Request should succeed after clearing
    $response = $this->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('info');
    Notification::assertSentTo($user, \App\Notifications\VerifyEmailNotification::class);
});

it('requires authentication to send verification notification', function () {
    $response = $this->post('/email/verification-notification');

    $response->assertRedirect('/login');
});

it('shows success toast after email verification', function () {
    Event::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    $token = \App\Models\EmailVerificationToken::generate($user);

    $response = $this->get(route('verification.verify', ['token' => $token->token]));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('success', 'Your email has been verified successfully! Please log in.');

    $user->refresh();
    expect($user->hasVerifiedEmail())->toBeTrue();
});
