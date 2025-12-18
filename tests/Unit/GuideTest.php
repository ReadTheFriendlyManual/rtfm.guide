<?php

use App\Models\Category;
use App\Models\Guide;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guide uses slug route key and published scope', function () {
    $visible = Guide::factory()->create(['status' => 'published', 'visibility' => 'public']);
    Guide::factory()->create(['status' => 'draft']);
    Guide::factory()->create(['visibility' => 'private']);

    expect($visible->getRouteKeyName())->toBe('slug');
    expect(Guide::query()->published()->pluck('id'))->toContain($visible->id);
    expect(Guide::query()->published()->count())->toBe(1);
});

test('guide reaction and saved counts respect eager loads', function () {
    $guide = Guide::factory()->create();
    $user = User::factory()->create();
    Reaction::factory()->count(2)->create(['guide_id' => $guide->id, 'type' => 'helpful']);
    $guide->savedBy()->attach($user->id);

    $fresh = Guide::query()->find($guide->id);
    expect($fresh->reactionCount('helpful'))->toBe(2);
    expect($fresh->savedCount())->toBe(1);

    $withCount = Guide::query()
        ->withCount([
            'savedBy as saved_by_count',
            'reactions as helpful_reactions_count' => fn ($query) => $query->where('type', 'helpful'),
        ])
        ->find($guide->id);

    expect($withCount->reactionCount('helpful'))->toBe(2);
    expect($withCount->savedCount())->toBe(1);
});

test('related guides stay inside the same category', function () {
    $category = Category::factory()->create();
    $peer = Guide::factory()->for($category)->create();
    $guide = Guide::factory()->for($category)->create();
    Guide::factory()->create(); // different category

    $related = $guide->related();

    expect($related->pluck('id'))->toContain($peer->id);
    expect($related->pluck('id'))->not->toContain($guide->id);
});
