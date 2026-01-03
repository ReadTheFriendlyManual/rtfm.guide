<?php

use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;

it('generates unique token for each user', function () {
    $user1 = User::factory()->create(['email_verified_at' => null]);
    $user2 = User::factory()->create(['email_verified_at' => null]);

    $token1 = EmailVerificationToken::generate($user1);
    $token2 = EmailVerificationToken::generate($user2);

    expect($token1->token)->not->toBe($token2->token);
    expect($token1->user_id)->toBe($user1->id);
    expect($token2->user_id)->toBe($user2->id);
});

it('replaces existing tokens when generating new one for same user', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $token1 = EmailVerificationToken::generate($user);
    $token2 = EmailVerificationToken::generate($user);

    expect($token1->token)->not->toBe($token2->token);
    expect(EmailVerificationToken::where('user_id', $user->id)->count())->toBe(1);
    expect(EmailVerificationToken::where('token', $token1->token)->exists())->toBeFalse();
    expect(EmailVerificationToken::where('token', $token2->token)->exists())->toBeTrue();
});

it('sets expiration to 24 hours in future', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $token = EmailVerificationToken::generate($user);

    // Verify expires_at is in the future
    expect($token->expires_at->isFuture())->toBeTrue();
    expect($token->isExpired())->toBeFalse();

    // Verify it's approximately 24 hours (give or take a second due to execution time)
    $hoursUntilExpiry = now()->diffInHours($token->expires_at, false);
    expect($hoursUntilExpiry)->toBeGreaterThanOrEqual(23);
    expect($hoursUntilExpiry)->toBeLessThanOrEqual(24);
});

it('correctly identifies expired tokens', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $expiredToken = EmailVerificationToken::create([
        'user_id' => $user->id,
        'token' => \Illuminate\Support\Str::random(64),
        'expires_at' => now()->subHour(),
    ]);

    expect($expiredToken->isExpired())->toBeTrue();
});

it('sends email with correct subject', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
        $mail = $notification->toMail($user);

        return $mail->subject === 'Verify Your Email Address - RTFM.guide';
    });
});

it('includes verification url in email', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
        $mail = $notification->toMail($user);
        $url = $mail->viewData['url'];

        expect($url)->toContain('email/verify/');

        return true;
    });
});

it('creates database token when sending notification', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    expect(EmailVerificationToken::where('user_id', $user->id)->exists())->toBeFalse();

    $user->sendEmailVerificationNotification();

    // Verify token was created (happens in verificationUrl method when notification is constructed)
    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
        // Trigger the toMail method which calls verificationUrl
        $notification->toMail($user);

        return true;
    });

    expect(EmailVerificationToken::where('user_id', $user->id)->exists())->toBeTrue();
});

it('url in email matches generated token', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
        $mail = $notification->toMail($user);
        $url = $mail->viewData['url'];

        $token = EmailVerificationToken::where('user_id', $user->id)->first();
        $expectedUrl = route('verification.verify', ['token' => $token->token]);

        expect($url)->toBe($expectedUrl);

        return true;
    });
});

it('uses custom email template', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) use ($user) {
        $mail = $notification->toMail($user);

        // Check that it uses the view we specified
        expect($mail->view)->toBe('emails.verify-email');
        expect($mail->viewData)->toHaveKey('url');

        return true;
    });
});

it('notification is queued', function () {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo($user, VerifyEmailNotification::class, function ($notification) {
        // Verify it implements ShouldQueue
        return $notification instanceof \Illuminate\Contracts\Queue\ShouldQueue;
    });
});

it('deletes token after successful verification', function () {
    $user = User::factory()->create(['email_verified_at' => null]);
    $token = EmailVerificationToken::generate($user);

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeTrue();

    $this->get(route('verification.verify', ['token' => $token->token]));

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeFalse();
});

it('deletes token even if user is already verified', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $token = EmailVerificationToken::generate($user);

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeTrue();

    $this->get(route('verification.verify', ['token' => $token->token]));

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeFalse();
});

it('deletes expired token when attempting verification', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $token = EmailVerificationToken::create([
        'user_id' => $user->id,
        'token' => \Illuminate\Support\Str::random(64),
        'expires_at' => now()->subHour(),
    ]);

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeTrue();

    $this->get(route('verification.verify', ['token' => $token->token]));

    expect(EmailVerificationToken::where('token', $token->token)->exists())->toBeFalse();
});
