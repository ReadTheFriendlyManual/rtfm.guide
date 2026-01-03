<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;

test('oauth redirect works for github', function () {
    $response = $this->get(route('oauth.redirect', 'github'));

    $response->assertRedirect();
});

test('oauth redirect works for google', function () {
    $response = $this->get(route('oauth.redirect', 'google'));

    $response->assertRedirect();
});

test('oauth redirect returns 404 for invalid provider', function () {
    $response = $this->get(route('oauth.redirect', 'invalid'));

    $response->assertNotFound();
});

test('oauth callback creates new user when oauth user does not exist', function () {
    $oauthUser = Mockery::mock(SocialiteUser::class);
    $oauthUser->shouldReceive('getId')->andReturn('123456');
    $oauthUser->shouldReceive('getEmail')->andReturn('newuser@example.com');
    $oauthUser->shouldReceive('getName')->andReturn('New User');
    $oauthUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver->user')->andReturn($oauthUser);

    $response = $this->get(route('oauth.callback', 'github'));

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticated();

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('New User');
    expect($user->oauth_provider)->toBe('github');
    expect($user->oauth_id)->toBe('123456');
    expect($user->avatar)->toBe('https://example.com/avatar.jpg');
    expect($user->email_verified_at)->not->toBeNull();
});

test('oauth callback links oauth to existing user with same email', function () {
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'password' => Hash::make('password'),
        'oauth_provider' => null,
        'oauth_id' => null,
    ]);

    $oauthUser = Mockery::mock(SocialiteUser::class);
    $oauthUser->shouldReceive('getId')->andReturn('789012');
    $oauthUser->shouldReceive('getEmail')->andReturn('existing@example.com');
    $oauthUser->shouldReceive('getName')->andReturn('Existing User');
    $oauthUser->shouldReceive('getAvatar')->andReturn('https://example.com/new-avatar.jpg');

    Socialite::shouldReceive('driver->user')->andReturn($oauthUser);

    $response = $this->get(route('oauth.callback', 'google'));

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticated();

    $existingUser->refresh();
    expect($existingUser->oauth_provider)->toBe('google');
    expect($existingUser->oauth_id)->toBe('789012');
    expect($existingUser->email_verified_at)->not->toBeNull();
});

test('oauth callback logs in existing oauth user', function () {
    $existingUser = User::factory()->create([
        'email' => 'oauth@example.com',
        'oauth_provider' => 'github',
        'oauth_id' => '111222',
    ]);

    $oauthUser = Mockery::mock(SocialiteUser::class);
    $oauthUser->shouldReceive('getId')->andReturn('111222');
    $oauthUser->shouldReceive('getEmail')->andReturn('oauth@example.com');
    $oauthUser->shouldReceive('getName')->andReturn('OAuth User');
    $oauthUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

    Socialite::shouldReceive('driver->user')->andReturn($oauthUser);

    $response = $this->get(route('oauth.callback', 'github'));

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticatedAs($existingUser);
});

test('oauth callback handles error gracefully', function () {
    Socialite::shouldReceive('driver->user')->andThrow(new Exception('OAuth error'));

    $response = $this->get(route('oauth.callback', 'github'));

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors(['oauth']);
    $this->assertGuest();
});

test('oauth callback returns 404 for invalid provider', function () {
    $response = $this->get(route('oauth.callback', 'invalid'));

    $response->assertNotFound();
});
