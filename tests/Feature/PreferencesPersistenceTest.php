<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can update their RTFM mode preference', function () {
    $user = User::factory()->create([
        'preferred_rtfm_mode' => 'sfw',
    ]);

    $this->actingAs($user)
        ->post('/api/preferences/mode', ['mode' => 'nsfw'])
        ->assertNoContent();

    expect($user->fresh()->preferred_rtfm_mode)->toBe('nsfw');
});

test('authenticated users can update their theme preference', function () {
    $user = User::factory()->create([
        'preferred_theme' => 'light',
    ]);

    $this->actingAs($user)
        ->post('/api/preferences/theme', ['theme' => 'dark'])
        ->assertNoContent();

    expect($user->fresh()->preferred_theme)->toBe('dark');
});

test('guest users cannot update preferences', function () {
    $this->post('/api/preferences/mode', ['mode' => 'nsfw'])
        ->assertRedirect('/login');

    $this->post('/api/preferences/theme', ['theme' => 'dark'])
        ->assertRedirect('/login');
});

test('preferences require valid values for mode', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/api/preferences/mode', ['mode' => 'invalid'])
        ->assertSessionHasErrors('mode');
});

test('preferences require valid values for theme', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/api/preferences/theme', ['theme' => 'invalid'])
        ->assertSessionHasErrors('theme');
});

test('user preferences are included in Inertia shared data', function () {
    $user = User::factory()->create([
        'preferred_rtfm_mode' => 'nsfw',
        'preferred_theme' => 'dark',
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertInertia(fn ($page) => $page
            ->where('auth.user.preferred_rtfm_mode', 'nsfw')
            ->where('auth.user.preferred_theme', 'dark')
        );
});

test('guide index includes both SFW and NSFW TLDRs', function () {
    \App\Models\Category::factory()->create();
    $guide = \App\Models\Guide::factory()->create([
        'tldr' => 'This is SFW',
        'tldr_nsfw' => 'This is NSFW you fucking idiot',
    ]);

    $response = $this->get('/guides');

    $response->assertInertia(fn ($page) => $page
        ->where('guides.data.0.tldr_sfw', 'This is SFW')
        ->where('guides.data.0.tldr_nsfw', 'This is NSFW you fucking idiot')
    );
});
