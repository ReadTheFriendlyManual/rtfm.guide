<?php

use App\Models\Guide;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('authenticated user can save a guide', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    $response = $this->actingAs($user)->post("/api/guides/{$guide->id}/save");

    $response->assertSuccessful();
    expect($user->fresh()->hasSavedGuide($guide->id))->toBeTrue();
});

test('authenticated user can unsave a guide', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    // First save it
    $user->savedGuides()->create(['guide_id' => $guide->id]);

    // Then unsave it
    $response = $this->actingAs($user)->delete("/api/guides/{$guide->id}/save");

    $response->assertSuccessful();
    expect($user->fresh()->hasSavedGuide($guide->id))->toBeFalse();
});

test('guest users cannot save guides', function () {
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    $response = $this->post("/api/guides/{$guide->id}/save");

    $response->assertRedirect('/login');
});

test('saved guides page shows user bookmarks', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $user->savedGuides()->create(['guide_id' => $guide->id]);

    $response = $this->actingAs($user)->get('/saved-guides');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('SavedGuides/Index')
        ->has('savedGuides.data', 1)
    );
});

test('guide show page indicates if guide is saved', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    $user->savedGuides()->create(['guide_id' => $guide->id]);

    $response = $this->actingAs($user)->get("/guides/{$guide->slug}");

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->where('guide.is_saved', true)
    );
});

test('cannot save same guide twice', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);

    // Save once
    $this->actingAs($user)->post("/api/guides/{$guide->id}/save");

    // Try to save again
    $response = $this->actingAs($user)->post("/api/guides/{$guide->id}/save");

    $response->assertSuccessful();
    expect($user->savedGuides()->count())->toBe(1);
});
