<?php

use App\Livewire\Guides\Index;
use App\Livewire\Guides\Show;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('guides index renders filters and can jump to a random guide', function () {
    $guide = Guide::factory()->create(['title' => 'Laravel deploy blueprint']);
    $other = Guide::factory()->create(['title' => 'Windows guide']);

    $component = Livewire::test(Index::class);
    $component->assertSee('RTFM.guide Library');
    $component->assertSee($guide->title);

    $component->set('search', 'deploy')
        ->assertSee($guide->title)
        ->assertViewHas('guides', function ($guides) use ($guide, $other) {
            return $guides->contains('id', $guide->id)
                && ! $guides->contains('id', $other->id);
        });

    $component->set('difficulty', $guide->difficulty)->assertSee($guide->title);

    $other->update(['status' => 'draft']);

    $component->call('feelingLucky')->assertRedirect(route('guides.show', $guide));
});

test('guide show tracks reactions and saves for authenticated users', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->create(['view_count' => 0]);

    $component = Livewire::actingAs($user)->test(Show::class, ['guide' => $guide]);
    $component->assertSee($guide->title);

    $component->call('toJSON')->assertReturned(function ($json) use ($guide) {
        $decoded = json_decode((string) $json, true);

        return data_get($decoded, 'id') === $guide->id
            && data_get($decoded, 'reactions_count') === 0
            && data_get($decoded, 'saves_count') === 0;
    });

    $component->call('react', 'helpful')->assertSet('reactionType', 'helpful');
    $component->call('toggleSave')->assertSet('saved', true);

    $guide->refresh();
    expect($guide->reactions()->where('user_id', $user->id)->first())->not->toBeNull();
    expect($guide->savedBy()->where('users.id', $user->id)->exists())->toBeTrue();
    expect($guide->view_count)->toBe(1);
});
