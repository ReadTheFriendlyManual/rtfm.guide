<?php

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('it can send an email to an individual user', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $this->artisan('task:resend-verification-email', ['user_id' => $user->id])
        ->expectsOutput("Resent verification email to user ID: $user->id")
        ->assertExitCode(0);

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('it can send emails to all unverified users', function () {
    Notification::fake();

    $unverifiedUsers = User::factory()->unverified()->count(3)->create();
    $verifiedUser = User::factory()->create();

    $this->artisan('task:resend-verification-email', ['user_id' => 'all'])
        ->expectsOutput("Resent verification email to user ID: {$unverifiedUsers[0]->id}")
        ->expectsOutput("Resent verification email to user ID: {$unverifiedUsers[1]->id}")
        ->expectsOutput("Resent verification email to user ID: {$unverifiedUsers[2]->id}")
        ->assertExitCode(0);

    Notification::assertSentTo($unverifiedUsers[0], VerifyEmailNotification::class);
    Notification::assertSentTo($unverifiedUsers[1], VerifyEmailNotification::class);
    Notification::assertSentTo($unverifiedUsers[2], VerifyEmailNotification::class);
    Notification::assertNotSentTo($verifiedUser, VerifyEmailNotification::class);
});

test('it does not send emails to verified users', function () {
    Notification::fake();

    $verifiedUser = User::factory()->create();

    $this->artisan('task:resend-verification-email', ['user_id' => $verifiedUser->id])
        ->expectsOutput('User not found or already verified.')
        ->assertExitCode(0);

    Notification::assertNotSentTo($verifiedUser, VerifyEmailNotification::class);
});

test('it handles non-existent users gracefully', function () {
    Notification::fake();

    $this->artisan('task:resend-verification-email', ['user_id' => 9999])
        ->expectsOutput('User not found or already verified.')
        ->assertExitCode(0);

    Notification::assertNothingSent();
});

test('it handles no unverified users gracefully', function () {
    Notification::fake();

    $verifiedUsers = User::factory()->count(3)->create();

    $this->artisan('task:resend-verification-email', ['user_id' => 'all'])
        ->assertExitCode(0);

    Notification::assertNothingSent();
});
