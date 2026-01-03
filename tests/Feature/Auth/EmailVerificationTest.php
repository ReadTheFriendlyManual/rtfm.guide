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

    $response = $this->get(route('verification.verify', ['token' => $token->token]));

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('login', absolute: false));
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
