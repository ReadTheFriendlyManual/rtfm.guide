<?php

use App\Models\Category;
use App\Models\Guide;

uses()->group('sitemap');

it('generates correct view route name from model class', function () {
    $category = new Category;

    expect($category->getViewRouteName())->toBe('categories.show');
});

it('generates correct view route name for guide model', function () {
    $guide = new Guide;

    expect($guide->getViewRouteName())->toBe('guides.show');
});

it('returns model as route parameter', function () {
    $category = Category::factory()->create();

    $parameters = $category->getRouteParameters();

    expect($parameters)->toBeArray()
        ->toHaveCount(1)
        ->and($parameters[0])->toBe($category);
});

it('generates absolute sitemap route by default', function () {
    $category = Category::factory()->create();

    $route = $category->getSitemapRoute();

    expect($route)
        ->toBeString()
        ->toContain('http')
        ->toContain('categories')
        ->toContain((string) $category->id);
});

it('generates relative sitemap route when requested', function () {
    $category = Category::factory()->create();

    $route = $category->getSitemapRoute(false);

    expect($route)
        ->toBeString()
        ->not->toContain('http')
        ->toContain('categories')
        ->toContain((string) $category->id);
});

it('generates correct sitemap route for guide model', function () {
    $guide = Guide::factory()->create();

    $route = $guide->getSitemapRoute();

    expect($route)
        ->toBeString()
        ->toContain('http')
        ->toContain('guides')
        ->toContain((string) $guide->id);
});
