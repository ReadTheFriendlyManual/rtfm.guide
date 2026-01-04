<?php

use App\Models\Category;
use App\Models\Flag;
use App\Models\Guide;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

it('can create a flag', function () {
    $flag = Flag::factory()->create([
        'name' => 'Medical Advice',
        'slug' => 'medical-advice',
        'description' => 'Contains medical information',
        'color' => 'red',
        'icon' => '⚕️',
    ]);

    assertDatabaseHas('flags', [
        'name' => 'Medical Advice',
        'slug' => 'medical-advice',
        'color' => 'red',
    ]);

    expect($flag->name)->toBe('Medical Advice');
    expect($flag->color)->toBe('red');
});

it('can attach flags to a guide', function () {
    $guide = Guide::factory()->create();
    $flag = Flag::factory()->medicalAdvice()->create();

    $guide->flags()->attach($flag, ['notes' => 'Consult a doctor']);

    expect($guide->flags)->toHaveCount(1);
    expect($guide->flags->first()->name)->toBe('Medical Advice');
    expect($guide->flags->first()->pivot->notes)->toBe('Consult a doctor');

    assertDatabaseHas('guide_flag', [
        'guide_id' => $guide->id,
        'flag_id' => $flag->id,
    ]);
});

it('can attach multiple flags to a guide', function () {
    $guide = Guide::factory()->create();
    $medicalFlag = Flag::factory()->medicalAdvice()->create();
    $outdatedFlag = Flag::factory()->outdated()->create();

    $guide->flags()->attach([
        $medicalFlag->id => ['notes' => 'Medical disclaimer'],
        $outdatedFlag->id => ['notes' => 'Applies to v1.0 only'],
    ]);

    expect($guide->flags)->toHaveCount(2);
});

it('can attach flags to a category', function () {
    $category = Category::factory()->create();
    $flag = Flag::factory()->legalAdvice()->create();

    $category->flags()->attach($flag, ['notes' => 'Legal disclaimer for all guides']);

    expect($category->flags)->toHaveCount(1);
    expect($category->flags->first()->name)->toBe('Legal Advice');

    assertDatabaseHas('category_flag', [
        'category_id' => $category->id,
        'flag_id' => $flag->id,
    ]);
});

it('displays flags on guide show page', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $guide = Guide::factory()
        ->for($user)
        ->for($category)
        ->create([
            'status' => 'published',
            'visibility' => 'public',
        ]);

    $flag = Flag::factory()->medicalAdvice()->create();
    $guide->flags()->attach($flag, ['notes' => 'For educational purposes only']);

    get("/guides/{$guide->slug}")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Guides/Show')
            ->has('guide.flags', 1)
            ->where('guide.flags.0.name', 'Medical Advice')
            ->where('guide.flags.0.color', 'red')
            ->where('guide.flags.0.pivot.notes', 'For educational purposes only'));
});

it('displays flags on category show page', function () {
    $category = Category::factory()->create();
    $flag = Flag::factory()->outdated()->create();

    $category->flags()->attach($flag, ['notes' => 'All guides in this category may be outdated']);

    get("/{$category->slug}")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Categories/Show')
            ->has('category.flags', 1)
            ->where('category.flags.0.name', 'Outdated')
            ->where('category.flags.0.color', 'yellow')
            ->where('category.flags.0.pivot.notes', 'All guides in this category may be outdated'));
});

it('can detach flags from guides', function () {
    $guide = Guide::factory()->create();
    $flag = Flag::factory()->create();

    $guide->flags()->attach($flag);
    expect($guide->flags)->toHaveCount(1);

    $guide->flags()->detach($flag);
    expect($guide->fresh()->flags)->toHaveCount(0);
});

it('orders flags correctly', function () {
    $guide = Guide::factory()->create();

    $flag1 = Flag::factory()->create(['order' => 10]);
    $flag2 = Flag::factory()->create(['order' => 5]);
    $flag3 = Flag::factory()->create(['order' => 15]);

    $guide->flags()->attach([$flag1->id, $flag2->id, $flag3->id]);

    $orderedFlags = $guide->flags()->get();

    expect($orderedFlags->first()->order)->toBe(5);
    expect($orderedFlags->last()->order)->toBe(15);
});

it('deletes pivot records when flag is deleted', function () {
    $guide = Guide::factory()->create();
    $flag = Flag::factory()->create();

    $guide->flags()->attach($flag);

    assertDatabaseHas('guide_flag', [
        'guide_id' => $guide->id,
        'flag_id' => $flag->id,
    ]);

    $flag->delete();

    expect($guide->fresh()->flags)->toHaveCount(0);
});
