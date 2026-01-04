<?php

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;

use function Pest\Laravel\get;

it('generates SEO data for guides', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $guide = Guide::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'title' => 'Test Guide Title',
        'tldr' => 'This is a test TLDR',
        'status' => 'published',
        'visibility' => 'public',
    ]);

    $seoData = $guide->getDynamicSEOData();

    expect($seoData->title)->toBe('Test Guide Title')
        ->and($seoData->description)->toBe('This is a test TLDR')
        ->and($seoData->author)->toBe($user->name);
});

it('generates SEO data for categories', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'description' => 'Test category description',
    ]);

    $seoData = $category->getDynamicSEOData();

    expect($seoData->title)->toBe('Test Category')
        ->and($seoData->description)->toBe('Test category description');
});

it('generates SEO data for users', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'bio' => 'Test bio',
    ]);

    $seoData = $user->getDynamicSEOData();

    expect($seoData->title)->toBe('John Doe - RTFM Profile')
        ->and($seoData->description)->toBe('Test bio')
        ->and($seoData->author)->toBe('John Doe');
});

it('generates OG image for guides', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $guide = Guide::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'published',
        'visibility' => 'public',
    ]);

    $response = get(route('og-images.guide', $guide));

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'image/png');

    expect($response->headers->get('Cache-Control'))
        ->toContain('public')
        ->toContain('max-age=31536000')
        ->toContain('immutable');
});

it('returns 404 for draft guide OG images', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $guide = Guide::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'draft',
        'visibility' => 'public',
    ]);

    $response = get(route('og-images.guide', $guide));

    $response->assertNotFound();
});

it('returns 404 for private guide OG images', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $guide = Guide::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'status' => 'published',
        'visibility' => 'private',
    ]);

    $response = get(route('og-images.guide', $guide));

    $response->assertNotFound();
});

it('generates OG image for categories', function () {
    $category = Category::factory()->create();

    $response = get(route('og-images.category', $category));

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'image/png');

    expect($response->headers->get('Cache-Control'))
        ->toContain('public')
        ->toContain('max-age=31536000')
        ->toContain('immutable');
});

it('generates OG image for users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('og-images.user', $user));

    $response->assertSuccessful()
        ->assertHeader('Content-Type', 'image/png');

    expect($response->headers->get('Cache-Control'))
        ->toContain('public')
        ->toContain('max-age=31536000')
        ->toContain('immutable');
});

it('requires authentication for user OG images', function () {
    $user = User::factory()->create();

    $response = get(route('og-images.user', $user));

    $response->assertRedirect(route('login'));
});

it('includes SEO meta tags on guide pages', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    $guide = Guide::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'slug' => 'seo-test-guide',
        'title' => 'SEO Test Guide',
        'status' => 'published',
        'visibility' => 'public',
    ]);

    $response = get(route('guides.show', $guide->slug));

    $response->assertSuccessful()
        ->assertSee('SEO Test Guide', false);
});

it('includes SEO meta tags on category pages', function () {
    $category = Category::factory()->create([
        'slug' => 'seo-test-category',
        'name' => 'SEO Test Category',
    ]);

    $response = get(route('categories.show', $category->slug));

    $response->assertSuccessful()
        ->assertSee('SEO Test Category', false);
});

it('includes SEO meta tags on home page', function () {
    $response = get(route('home'));

    $response->assertSuccessful()
        ->assertSee('RTFM', false);
});
