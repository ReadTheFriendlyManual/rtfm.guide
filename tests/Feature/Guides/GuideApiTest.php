<?php

use App\Models\Category;
use App\Models\Guide;
use App\Models\Reaction;
use App\Models\SavedGuide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('api lists published guides with filters and metadata', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
    $otherCategory = Category::factory()->create(['name' => 'Docker', 'slug' => 'docker']);

    $match = Guide::factory()
        ->for($author, 'author')
        ->for($category)
        ->create([
            'title' => 'Zero downtime deployment',
            'difficulty' => 'advanced',
            'os_tags' => ['linux'],
        ]);

    Guide::factory()->for($author, 'author')->for($otherCategory)->create([
        'title' => 'Windows deploy guide',
        'difficulty' => 'beginner',
    ]);

    $response = $this->getJson(route('api.guides.index', [
        'q' => 'deployment',
        'difficulty' => 'advanced',
        'category' => $category->slug,
        'per_page' => 15,
    ]));

    $response->assertSuccessful();
    $response->assertJsonPath('data.0.slug', $match->slug);
    $response->assertJsonPath('data.0.category.slug', $category->slug);
    $response->assertJsonPath('data.0.stats.reactions.helpful', 0);
    $response->assertJsonPath('meta.per_page', 15);
});

test('api hides drafts and private guides and shows stats', function () {
    $author = User::factory()->create();

    $visible = Guide::factory()->for($author, 'author')->create();
    $hidden = Guide::factory()->for($author, 'author')->create(['status' => 'draft']);
    $private = Guide::factory()->for($author, 'author')->create(['visibility' => 'private']);

    Reaction::factory()->create(['guide_id' => $visible->id, 'user_id' => $author->id, 'type' => 'helpful']);
    SavedGuide::factory()->create(['guide_id' => $visible->id, 'user_id' => $author->id]);

    $indexResponse = $this->getJson(route('api.guides.index'));
    $slugs = collect($indexResponse->json('data'))->pluck('slug');
    expect($slugs)->not->toContain($hidden->slug);
    expect($slugs)->not->toContain($private->slug);

    $showResponse = $this->getJson(route('api.guides.show', $visible));
    $showResponse->assertSuccessful();
    $showResponse->assertJsonPath('data.stats.reactions.helpful', 1);
    $showResponse->assertJsonPath('data.stats.saves', 1);

    $this->getJson(route('api.guides.show', $hidden))->assertNotFound();
});

test('api returns trending and random guides ordered by engagement', function () {
    $top = Guide::factory()->create(['view_count' => 900, 'share_count' => 20]);
    $middle = Guide::factory()->create(['view_count' => 400, 'share_count' => 5]);
    $low = Guide::factory()->create(['view_count' => 10, 'share_count' => 1]);

    $trending = $this->getJson(route('api.guides.trending'));
    $trending->assertSuccessful();
    $trending->assertJsonPath('data.0.slug', $top->slug);
    $trending->assertJsonPath('data.1.slug', $middle->slug);

    $random = $this->getJson(route('api.guides.random'));
    $random->assertSuccessful();
    expect(collect([$top->slug, $middle->slug, $low->slug]))->toContain($random->json('data.slug'));
});
