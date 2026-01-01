<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{get, post};

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure settings table exists and has default data
    if (! Setting::first()) {
        Setting::create([
            'registration_enabled' => true,
            'login_enabled' => true,
            'registration_disabled_message' => null,
            'login_disabled_message' => null,
        ]);
    }
});

it('allows registration when registration is enabled', function () {
    $settings = Setting::first();
    $settings->update(['registration_enabled' => true]);
    Setting::clearCache();

    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    expect(User::where('email', 'test@example.com')->exists())->toBeTrue();
});

it('blocks registration when registration is disabled', function () {
    $settings = Setting::first();
    $settings->update([
        'registration_enabled' => false,
        'registration_disabled_message' => 'Registration is temporarily disabled.',
    ]);
    Setting::clearCache();

    $response = post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});

it('shows registration disabled message in register view', function () {
    $settings = Setting::first();
    $settings->update([
        'registration_enabled' => false,
        'registration_disabled_message' => 'Custom disabled message',
    ]);
    Setting::clearCache();

    $response = get('/register');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Register')
        ->where('registrationEnabled', false)
        ->where('registrationDisabledMessage', 'Custom disabled message')
    );
});

it('allows login when login is enabled', function () {
    $settings = Setting::first();
    $settings->update(['login_enabled' => true]);
    Setting::clearCache();

    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
});

it('blocks login when login is disabled', function () {
    $settings = Setting::first();
    $settings->update([
        'login_enabled' => false,
        'login_disabled_message' => 'Login is temporarily disabled.',
    ]);
    Setting::clearCache();

    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('shows login disabled message in login view', function () {
    $settings = Setting::first();
    $settings->update([
        'login_enabled' => false,
        'login_disabled_message' => 'Custom login disabled message',
    ]);
    Setting::clearCache();

    $response = get('/login');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Login')
        ->where('loginEnabled', false)
        ->where('loginDisabledMessage', 'Custom login disabled message')
    );
});

it('uses default message when custom message is null', function () {
    $settings = Setting::first();
    $settings->update([
        'registration_enabled' => false,
        'registration_disabled_message' => null,
    ]);
    Setting::clearCache();

    $response = get('/register');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Register')
        ->where('registrationEnabled', false)
        ->where('registrationDisabledMessage', 'Registration is currently disabled.')
    );
});

it('clears cache when settings are updated', function () {
    $settings = Setting::first();

    // Get cached value
    $cached1 = Setting::current();
    expect($cached1->registration_enabled)->toBeTrue();

    // Update settings
    $settings->update(['registration_enabled' => false]);

    // Verify cache was cleared
    $cached2 = Setting::current();
    expect($cached2->registration_enabled)->toBeFalse();
});
