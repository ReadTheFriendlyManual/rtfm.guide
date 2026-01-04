<?php

use App\Models\Category;
use App\Models\Guide;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

beforeEach(function () {
    // Clean up any existing sitemap files
    $publicPath = public_path();
    File::delete($publicPath.'/sitemap.xml');
    if (File::exists($publicPath.'/sitemaps')) {
        File::deleteDirectory($publicPath.'/sitemaps');
    }
});

afterEach(function () {
    // Clean up after tests
    $publicPath = public_path();
    File::delete($publicPath.'/sitemap.xml');
    if (File::exists($publicPath.'/sitemaps')) {
        File::deleteDirectory($publicPath.'/sitemaps');
    }
});

it('creates sitemaps directory if it does not exist', function () {
    $sitemapsPath = public_path('sitemaps');

    expect(File::exists($sitemapsPath))->toBeFalse();

    artisan('sitemap:generate')->assertSuccessful();

    expect(File::exists($sitemapsPath))->toBeTrue();
});

it('generates main sitemap index file', function () {
    artisan('sitemap:generate')->assertSuccessful();

    $sitemapPath = public_path('sitemap.xml');

    expect(File::exists($sitemapPath))->toBeTrue();

    $content = File::get($sitemapPath);
    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
});

it('generates general pages sitemap', function () {
    artisan('sitemap:generate')->assertSuccessful();

    $generalSitemapPath = public_path('sitemaps/sitemap-general.xml');

    expect(File::exists($generalSitemapPath))->toBeTrue();

    $content = File::get($generalSitemapPath);
    expect($content)
        ->toContain(route('home'))
        ->toContain(route('login'))
        ->toContain(route('register'))
        ->toContain(route('categories.index'))
        ->toContain(route('guides.index'))
        ->toContain(route('search.index'));
});

it('generates sitemap for categories', function () {
    Category::factory()->count(5)->create();

    artisan('sitemap:generate')->assertSuccessful();

    $categorySitemapPath = public_path('sitemaps/sitemap-category-1.xml');

    expect(File::exists($categorySitemapPath))->toBeTrue();

    $content = File::get($categorySitemapPath);
    $categories = Category::all();

    foreach ($categories as $category) {
        expect($content)->toContain($category->getSitemapRoute(true));
    }
});

it('generates sitemap for guides', function () {
    Guide::factory()->count(3)->create();

    artisan('sitemap:generate')->assertSuccessful();

    $guideSitemapPath = public_path('sitemaps/sitemap-guide-1.xml');

    expect(File::exists($guideSitemapPath))->toBeTrue();

    $content = File::get($guideSitemapPath);
    $guides = Guide::all();

    foreach ($guides as $guide) {
        expect($content)->toContain($guide->getSitemapRoute(true));
    }
});

it('includes all sitemaps in the main index', function () {
    Category::factory()->count(2)->create();
    Guide::factory()->count(2)->create();

    artisan('sitemap:generate')->assertSuccessful();

    $indexContent = File::get(public_path('sitemap.xml'));

    expect($indexContent)
        ->toContain('sitemap-category-1.xml')
        ->toContain('sitemap-guide-1.xml');
});

it('validates entities must be subclass of SitemapableModel', function () {
    $command = new \App\Console\Commands\GenerateSitemapCommand;

    expect(fn () => $command->processSitemapFor([\stdClass::class]))
        ->toThrow(\InvalidArgumentException::class);
});

it('validates entity class must exist', function () {
    $command = new \App\Console\Commands\GenerateSitemapCommand;

    expect(fn () => $command->processSitemapFor(['NonExistentClass']))
        ->toThrow(\InvalidArgumentException::class, "The provided entity class 'NonExistentClass' does not exist");
});

it('accepts collection of entities', function () {
    Category::factory()->count(2)->create();

    $command = new \App\Console\Commands\GenerateSitemapCommand;

    expect(fn () => $command->processSitemapFor(collect([Category::class])))
        ->not->toThrow(\InvalidArgumentException::class);
});

it('accepts string entity class name', function () {
    Category::factory()->count(2)->create();

    $command = new \App\Console\Commands\GenerateSitemapCommand;

    expect(fn () => $command->processSitemapFor(Category::class))
        ->not->toThrow(\InvalidArgumentException::class);
});
