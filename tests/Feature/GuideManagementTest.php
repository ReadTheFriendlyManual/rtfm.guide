<?php

use App\Enums\GuideDifficulty;
use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\{actingAs, assertDatabaseHas, get, post, put};

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
});

it('shows the create guide page to authenticated users', function () {
    actingAs($this->user)
        ->get(route('guides.create'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Guides/Create')
            ->has('categories')
            ->has('difficulties')
            ->has('osTags'));
});

it('redirects unauthenticated users from create guide page', function () {
    get(route('guides.create'))
        ->assertRedirect(route('login'));
});

it('can create a guide as draft', function () {
    $guideData = [
        'title' => 'How to Restart Nginx',
        'tldr' => 'A quick guide on restarting Nginx web server',
        'content' => '## Steps\n\n1. Run `sudo systemctl restart nginx`',
        'category_id' => $this->category->id,
        'difficulty' => GuideDifficulty::Beginner->value,
        'estimated_minutes' => 5,
        'os_tags' => ['Linux', 'Ubuntu'],
        'status' => GuideStatus::Draft->value,
    ];

    actingAs($this->user)
        ->post(route('guides.store'), $guideData)
        ->assertRedirect();

    assertDatabaseHas('guides', [
        'user_id' => $this->user->id,
        'title' => 'How to Restart Nginx',
        'slug' => 'how-to-restart-nginx',
        'status' => GuideStatus::Draft->value,
    ]);

    $guide = Guide::where('slug', 'how-to-restart-nginx')->first();
    expect($guide->published_at)->toBeNull();
});

it('can create a guide pending review', function () {
    $guideData = [
        'title' => 'Docker Compose Tutorial',
        'tldr' => 'Learn Docker Compose basics',
        'content' => '## Introduction\n\nDocker Compose is great!',
        'category_id' => $this->category->id,
        'difficulty' => GuideDifficulty::Intermediate->value,
        'estimated_minutes' => 30,
        'status' => GuideStatus::Pending->value,
    ];

    actingAs($this->user)
        ->post(route('guides.store'), $guideData)
        ->assertRedirect();

    assertDatabaseHas('guides', [
        'user_id' => $this->user->id,
        'title' => 'Docker Compose Tutorial',
        'status' => GuideStatus::Pending->value,
    ]);
});

it('automatically generates slug from title', function () {
    $guideData = [
        'title' => 'How to Install MySQL on Ubuntu 22.04',
        'tldr' => 'Installation guide for MySQL',
        'content' => 'Content here',
        'category_id' => $this->category->id,
        'difficulty' => GuideDifficulty::Beginner->value,
    ];

    actingAs($this->user)
        ->post(route('guides.store'), $guideData)
        ->assertRedirect();

    assertDatabaseHas('guides', [
        'slug' => 'how-to-install-mysql-on-ubuntu-2204',
    ]);
});

it('validates required fields when creating a guide', function () {
    actingAs($this->user)
        ->post(route('guides.store'), [])
        ->assertSessionHasErrors(['title', 'tldr', 'content', 'category_id', 'difficulty']);
});

it('validates unique slug when creating a guide', function () {
    Guide::factory()->create([
        'user_id' => $this->user->id,
        'slug' => 'existing-guide',
        'title' => 'Existing Guide',
    ]);

    actingAs($this->user)
        ->post(route('guides.store'), [
            'title' => 'Existing Guide',
            'tldr' => 'TL;DR',
            'content' => 'Content',
            'category_id' => $this->category->id,
            'difficulty' => GuideDifficulty::Beginner->value,
        ])
        ->assertSessionHasErrors(['slug']);
});

it('validates tldr max length', function () {
    actingAs($this->user)
        ->post(route('guides.store'), [
            'title' => 'Test Guide',
            'tldr' => str_repeat('a', 1001),
            'content' => 'Content',
            'category_id' => $this->category->id,
            'difficulty' => GuideDifficulty::Beginner->value,
        ])
        ->assertSessionHasErrors(['tldr']);
});

it('can view list of own guides', function () {
    Guide::factory()->count(3)->create(['user_id' => $this->user->id]);
    Guide::factory()->count(2)->create(); // Other user's guides

    actingAs($this->user)
        ->get(route('guides.my'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Guides/MyGuides')
            ->has('guides.data', 3));
});

it('shows edit page for own guide', function () {
    $guide = Guide::factory()->create(['user_id' => $this->user->id]);

    actingAs($this->user)
        ->get(route('guides.edit', $guide))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Guides/Edit')
            ->has('guide')
            ->where('guide.id', $guide->id));
});

it('prevents editing other users guides', function () {
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->create(['user_id' => $otherUser->id]);

    actingAs($this->user)
        ->get(route('guides.edit', $guide))
        ->assertForbidden();
});

it('can update own guide', function () {
    $guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Old Title',
    ]);

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => 'New Title',
            'slug' => 'new-title',
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
        ])
        ->assertRedirect();

    $guide->refresh();
    expect($guide->title)->toBe('New Title');
    expect($guide->slug)->toBe('new-title');
});

it('prevents updating other users guides', function () {
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->create(['user_id' => $otherUser->id]);

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => 'Hacked Title',
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
        ])
        ->assertForbidden();

    $guide->refresh();
    expect($guide->title)->not->toBe('Hacked Title');
});

it('sets published_at when status changes to published', function () {
    $guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'status' => GuideStatus::Draft,
        'published_at' => null,
    ]);

    expect($guide->published_at)->toBeNull();

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => $guide->title,
            'slug' => $guide->slug,
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
            'status' => GuideStatus::Published->value,
        ])
        ->assertRedirect();

    $guide->refresh();
    expect($guide->status)->toBe(GuideStatus::Published);
    expect($guide->published_at)->not->toBeNull();
});

it('allows updating slug if unique', function () {
    $guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'slug' => 'old-slug',
    ]);

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => $guide->title,
            'slug' => 'new-unique-slug',
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
        ])
        ->assertRedirect();

    $guide->refresh();
    expect($guide->slug)->toBe('new-unique-slug');
});

it('prevents updating slug to existing slug', function () {
    Guide::factory()->create(['slug' => 'existing-slug']);

    $guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'slug' => 'my-slug',
    ]);

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => $guide->title,
            'slug' => 'existing-slug',
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
        ])
        ->assertSessionHasErrors(['slug']);
});

it('can update os_tags', function () {
    $guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'os_tags' => ['Linux'],
    ]);

    actingAs($this->user)
        ->put(route('guides.update', $guide), [
            'title' => $guide->title,
            'slug' => $guide->slug,
            'tldr' => $guide->tldr,
            'content' => $guide->content,
            'category_id' => $guide->category_id,
            'difficulty' => $guide->difficulty->value,
            'os_tags' => ['Linux', 'Ubuntu', 'Docker'],
        ])
        ->assertRedirect();

    $guide->refresh();
    expect($guide->os_tags)->toBe(['Linux', 'Ubuntu', 'Docker']);
});

it('defaults to draft status when creating guide without status', function () {
    actingAs($this->user)
        ->post(route('guides.store'), [
            'title' => 'Test Guide',
            'tldr' => 'TL;DR',
            'content' => 'Content',
            'category_id' => $this->category->id,
            'difficulty' => GuideDifficulty::Beginner->value,
        ])
        ->assertRedirect();

    $guide = Guide::where('title', 'Test Guide')->first();
    expect($guide->status)->toBe(GuideStatus::Draft);
});

it('defaults to public visibility when creating guide', function () {
    actingAs($this->user)
        ->post(route('guides.store'), [
            'title' => 'Test Guide',
            'tldr' => 'TL;DR',
            'content' => 'Content',
            'category_id' => $this->category->id,
            'difficulty' => GuideDifficulty::Beginner->value,
        ])
        ->assertRedirect();

    $guide = Guide::where('title', 'Test Guide')->first();
    expect($guide->visibility)->toBe(GuideVisibility::Public);
});
