<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('verification.notice'));

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $token = \App\Models\EmailVerificationToken::generate($user);

    $response = $this->get(route('verification.verify', ['token' => $token->rawToken]));

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('email is not verified with invalid token', function () {
    $user = User::factory()->unverified()->create();

    $this->get(route('verification.verify', ['token' => 'invalid-token']));

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('unverified users cannot access verified routes', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));
});

test('verified users can access verified routes', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});

test('verification email uses custom template', function () {
    $user = User::factory()->unverified()->create();

    Illuminate\Support\Facades\Notification::fake();

    $user->sendEmailVerificationNotification();

    Illuminate\Support\Facades\Notification::assertSentTo(
        $user,
        \App\Notifications\VerifyEmailNotification::class,
        function ($notification, $channels) use ($user) {
            $mail = $notification->toMail($user);

            return $mail->subject === 'Verify Your Email Address - RTFM.guide'
                && $mail->viewData['url'] !== null;
        }
    );
});

test('user is automatically logged in after successful email verification', function () {
    $user = User::factory()->unverified()->create();

    $token = \App\Models\EmailVerificationToken::generate($user);

    // Ensure user is not authenticated before verification
    expect(auth()->check())->toBeFalse();

    $response = $this->get(route('verification.verify', ['token' => $token->rawToken]));

    // User should be logged in after verification
    expect(auth()->check())->toBeTrue();
    expect(auth()->id())->toBe($user->id);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('already verified email does not auto-login user', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $token = \App\Models\EmailVerificationToken::generate($user);

    // Ensure user is not authenticated before clicking link
    expect(auth()->check())->toBeFalse();

    $response = $this->get(route('verification.verify', ['token' => $token->rawToken]));

    // User should NOT be logged in for already-verified emails
    expect(auth()->check())->toBeFalse();
    $response->assertRedirect(route('dashboard', absolute: false));
});
