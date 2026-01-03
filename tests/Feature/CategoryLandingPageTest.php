<?php

use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Category;
use App\Models\Guide;
use App\Models\User;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->category = Category::factory()->create([
        'name' => 'Web Development',
        'slug' => 'web-development',
        'description' => 'Learn web development from the basics to advanced topics.',
    ]);
});

it('displays category landing page with category information', function () {
    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Categories/Show')
            ->has('category', fn ($category) => $category
                ->where('name', 'Web Development')
                ->where('slug', 'web-development')
                ->where('description', 'Learn web development from the basics to advanced topics.')
            )
        );
});

it('displays featured guides on category landing page', function () {
    $featuredGuide = Guide::factory()->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Published,
        'visibility' => GuideVisibility::Public,
        'is_featured' => true,
        'title' => 'Featured Guide',
    ]);

    $normalGuide = Guide::factory()->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Published,
        'visibility' => GuideVisibility::Public,
        'is_featured' => false,
    ]);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Categories/Show')
            ->has('featuredGuides', 1)
            ->has('featuredGuides.0', fn ($guide) => $guide
                ->where('title', 'Featured Guide')
                ->where('is_featured', true)
            )
        );
});

it('only displays published and public featured guides', function () {
    Guide::factory()->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Draft,
        'visibility' => GuideVisibility::Public,
        'is_featured' => true,
    ]);

    Guide::factory()->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Published,
        'visibility' => GuideVisibility::Private,
        'is_featured' => true,
    ]);

    $publishedFeatured = Guide::factory()->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Published,
        'visibility' => GuideVisibility::Public,
        'is_featured' => true,
    ]);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->has('featuredGuides', 1)
            ->has('featuredGuides.0', fn ($guide) => $guide
                ->where('id', $publishedFeatured->id)
            )
        );
});

it('displays featured writers on category landing page', function () {
    $featuredWriter = User::factory()->create([
        'name' => 'Jane Doe',
        'bio' => 'Expert web developer',
    ]);

    $this->category->featuredWriters()->attach($featuredWriter->id, ['order' => 1]);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Categories/Show')
            ->has('featuredWriters', 1)
            ->has('featuredWriters.0', fn ($writer) => $writer
                ->where('name', 'Jane Doe')
                ->where('bio', 'Expert web developer')
            )
        );
});

it('displays featured writers with social links', function () {
    $featuredWriter = User::factory()->create([
        'github_username' => 'janedoe',
        'twitter_username' => 'janedoe',
        'linkedin_username' => 'janedoe',
        'website_url' => 'https://janedoe.com',
    ]);

    $this->category->featuredWriters()->attach($featuredWriter->id);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->has('featuredWriters.0', fn ($writer) => $writer
                ->where('github_username', 'janedoe')
                ->where('twitter_username', 'janedoe')
                ->where('linkedin_username', 'janedoe')
                ->where('website_url', 'https://janedoe.com')
            )
        );
});

it('orders featured writers correctly', function () {
    $writer1 = User::factory()->create(['name' => 'Writer 1']);
    $writer2 = User::factory()->create(['name' => 'Writer 2']);
    $writer3 = User::factory()->create(['name' => 'Writer 3']);

    $this->category->featuredWriters()->attach($writer1->id, ['order' => 2]);
    $this->category->featuredWriters()->attach($writer2->id, ['order' => 1]);
    $this->category->featuredWriters()->attach($writer3->id, ['order' => 3]);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->has('featuredWriters', 3)
            ->where('featuredWriters.0.name', 'Writer 2')
            ->where('featuredWriters.1.name', 'Writer 1')
            ->where('featuredWriters.2.name', 'Writer 3')
        );
});

it('limits featured guides to 6', function () {
    Guide::factory()->count(10)->create([
        'category_id' => $this->category->id,
        'status' => GuideStatus::Published,
        'visibility' => GuideVisibility::Public,
        'is_featured' => true,
    ]);

    get(route('category.landing', ['category' => $this->category->slug]))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->has('featuredGuides', 6)
        );
});

it('returns 404 for non-existent category', function () {
    get(route('category.landing', ['category' => 'non-existent-category']))
        ->assertNotFound();
});

it('can be accessed via both routes', function () {
    get("/categories/{$this->category->slug}")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page->component('Categories/Show'));

    get("/{$this->category->slug}")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page->component('Categories/Show'));
});
