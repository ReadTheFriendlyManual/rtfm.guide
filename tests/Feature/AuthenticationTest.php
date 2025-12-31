<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\postJson;

it('allows users to login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = postJson(route('api.login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'token',
            'expires_in',
            'token_type',
            'user' => ['id', 'name', 'email'],
        ]);

    expect($response->json('token'))->not->toBeEmpty();
    expect($response->json('expires_in'))->toBe(3600);
    expect($response->json('token_type'))->toBe('Bearer');
    expect($response->json('user.email'))->toBe('test@example.com');
});

it('returns the authenticated user information', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = postJson(route('api.login'), [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertSuccessful();

    expect($response->json('user.id'))->toBe($user->id);
    expect($response->json('user.name'))->toBe('John Doe');
    expect($response->json('user.email'))->toBe('john@example.com');
});

it('rejects login with invalid email', function () {
    postJson(route('api.login'), [
        'email' => 'nonexistent@example.com',
        'password' => 'password123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'The provided credentials are incorrect.']);
});

it('rejects login with invalid password', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('correctpassword'),
    ]);

    postJson(route('api.login'), [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'The provided credentials are incorrect.']);
});

it('validates that email is required', function () {
    postJson(route('api.login'), [
        'password' => 'password123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'Please provide your email address.']);
});

it('validates that email format is correct', function () {
    postJson(route('api.login'), [
        'email' => 'not-an-email',
        'password' => 'password123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => 'Please provide a valid email address.']);
});

it('validates that password is required', function () {
    postJson(route('api.login'), [
        'email' => 'test@example.com',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['password' => 'Please provide your password.']);
});

it('returns a valid token that can be used for authentication', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $loginResponse = postJson(route('api.login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $token = $loginResponse->json('token');

    $response = postJson(route('api.guide-imports.upload'), [], [
        'Authorization' => "Bearer {$token}",
    ]);

    $response->assertUnprocessable();
});
