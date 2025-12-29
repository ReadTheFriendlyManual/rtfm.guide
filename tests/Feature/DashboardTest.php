<?php

use App\Models\Guide;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Dashboard/Index')
        ->has('stats')
    );
});

test('dashboard shows user stats', function () {
    $user = User::factory()->create();
    Guide::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->where('stats.guides_created', 3)
    );
});

test('dashboard shows recent saved guides', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $user->savedGuides()->create(['guide_id' => $guide->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->has('recentSaved', 1)
    );
});

test('dashboard shows recent created guides', function () {
    $user = User::factory()->create();
    Guide::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->has('myRecentGuides', 2)
    );
});
