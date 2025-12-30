<?php

use App\Enums\GuideStatus;
use App\Enums\GuideVisibility;
use App\Models\Guide;

it('is searchable when published and public', function () {
    $guide = Guide::factory()->published()->create([
        'visibility' => GuideVisibility::Public,
    ]);

    expect($guide->shouldBeSearchable())->toBeTrue();
});

it('is not searchable when draft', function () {
    $guide = Guide::factory()->draft()->create([
        'visibility' => GuideVisibility::Public,
    ]);

    expect($guide->shouldBeSearchable())->toBeFalse();
});

it('is not searchable when private', function () {
    $guide = Guide::factory()->published()->private()->create();

    expect($guide->shouldBeSearchable())->toBeFalse();
});

it('is not searchable when draft and private', function () {
    $guide = Guide::factory()->draft()->private()->create();

    expect($guide->shouldBeSearchable())->toBeFalse();
});

it('is not searchable when pending', function () {
    $guide = Guide::factory()->create([
        'status' => GuideStatus::Pending,
        'visibility' => GuideVisibility::Public,
    ]);

    expect($guide->shouldBeSearchable())->toBeFalse();
});
