<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('allows registration when registration is enabled', function () {
    Setting::set('registration_enabled', true);

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
    Setting::set('registration_enabled', false);
    Setting::set('registration_disabled_message', 'Registration is temporarily disabled.');

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
    Setting::set('registration_enabled', false);
    Setting::set('registration_disabled_message', 'Custom disabled message');

    $response = get('/register');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Register')
        ->where('registrationEnabled', false)
        ->where('registrationDisabledMessage', 'Custom disabled message')
    );
});

it('allows login when login is enabled', function () {
    Setting::set('login_enabled', true);

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
    Setting::set('login_enabled', false);
    Setting::set('login_disabled_message', 'Login is temporarily disabled.');

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
    Setting::set('login_enabled', false);
    Setting::set('login_disabled_message', 'Custom login disabled message');

    $response = get('/login');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Login')
        ->where('loginEnabled', false)
        ->where('loginDisabledMessage', 'Custom login disabled message')
    );
});

it('uses default message when custom message is null', function () {
    Setting::set('registration_enabled', false);

    $response = get('/register');

    $response->assertInertia(fn ($page) => $page
        ->component('Auth/Register')
        ->where('registrationEnabled', false)
        ->where('registrationDisabledMessage', 'Registration is currently disabled.')
    );
});

it('clears cache when settings are updated', function () {
    Setting::set('registration_enabled', true);

    // Get cached value
    $cached1 = Setting::get('registration_enabled');
    expect($cached1)->toBeTrue();

    // Update setting
    Setting::set('registration_enabled', false);

    // Verify cache was cleared
    $cached2 = Setting::get('registration_enabled');
    expect($cached2)->toBeFalse();
});

it('can get and set different setting types', function () {
    // Boolean
    Setting::set('test_boolean', true);
    expect(Setting::get('test_boolean'))->toBeTrue();

    // Text
    Setting::set('test_text', 'Hello World');
    expect(Setting::get('test_text'))->toBe('Hello World');

    // Integer
    Setting::set('test_integer', 42);
    expect(Setting::get('test_integer'))->toBe(42);

    // JSON
    Setting::set('test_json', ['foo' => 'bar', 'baz' => 123]);
    expect(Setting::get('test_json'))->toBe(['foo' => 'bar', 'baz' => 123]);
});

it('returns default value when setting does not exist', function () {
    expect(Setting::get('nonexistent_setting', 'default'))->toBe('default');
    expect(Setting::get('nonexistent_boolean', true))->toBeTrue();
});
