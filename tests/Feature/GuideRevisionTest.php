<?php

use App\Models\Category;
use App\Models\Guide;
use App\Models\GuideRevision;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->trustedUser = User::factory()->create(['trusted_editor' => true]);
    $this->category = Category::factory()->create();
    $this->guide = Guide::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'published',
    ]);
});

it('creates a revision when non-trusted user edits a guide', function () {
    $updates = [
        'title' => 'Updated Title',
        'tldr' => 'Updated TLDR',
        'content' => '## Updated Content',
        'category_id' => $this->category->id,
        'difficulty' => 'intermediate',
        'estimated_minutes' => 15,
        'os_tags' => ['Linux'],
    ];

    actingAs($this->user)
        ->put(route('guides.update', $this->guide), $updates)
        ->assertRedirect()
        ->assertSessionHas('success', 'Your edits have been submitted for review!');

    // Guide should not be updated directly
    $this->guide->refresh();
    expect($this->guide->title)->not->toBe('Updated Title');

    // Revision should be created
    assertDatabaseHas('guide_revisions', [
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'title' => 'Updated Title',
        'status' => 'pending',
    ]);
});

it('updates guide directly when trusted user edits', function () {
    $updates = [
        'title' => 'Updated Title',
        'tldr' => 'Updated TLDR',
        'content' => '## Updated Content',
        'category_id' => $this->category->id,
        'difficulty' => 'intermediate',
        'estimated_minutes' => 15,
        'os_tags' => ['Linux'],
    ];

    actingAs($this->trustedUser)
        ->put(route('guides.update', $this->guide), $updates)
        ->assertRedirect()
        ->assertSessionHas('success', 'Guide updated successfully!');

    // Guide should be updated directly
    $this->guide->refresh();
    expect($this->guide->title)->toBe('Updated Title');

    // No revision should be created
    expect(GuideRevision::count())->toBe(0);
});

it('shows pending revision info on edit page', function () {
    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    actingAs($this->user)
        ->get(route('guides.edit', $this->guide))
        ->assertInertia(fn ($page) => $page
            ->component('Guides/Edit')
            ->where('guide.has_pending_revision', true)
            ->where('pendingRevision.id', $revision->id)
        );
});

it('shows pending revision notice on guide show page', function () {
    GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $this->get(route('guides.show', $this->guide->slug))
        ->assertInertia(fn ($page) => $page
            ->component('Guides/Show')
            ->where('guide.has_pending_revision', true)
        );
});

it('allows moderator to approve revision', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'title' => 'New Title',
        'status' => 'pending',
    ]);

    actingAs($moderator)
        ->post(route('revisions.approve', $revision))
        ->assertRedirect(route('revisions.index'))
        ->assertSessionHas('success', 'Revision approved and applied to the guide!');

    // Revision should be marked as approved
    $revision->refresh();
    expect($revision->status)->toBe('approved');
    expect($revision->approved_by)->toBe($moderator->id);
    expect($revision->approved_at)->not->toBeNull();

    // Guide should be updated with revision content
    $this->guide->refresh();
    expect($this->guide->title)->toBe('New Title');
});

it('allows moderator to reject revision', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    actingAs($moderator)
        ->post(route('revisions.reject', $revision), [
            'rejection_reason' => 'Content does not meet quality standards',
        ])
        ->assertRedirect(route('revisions.index'))
        ->assertSessionHas('success', 'Revision rejected.');

    // Revision should be marked as rejected
    $revision->refresh();
    expect($revision->status)->toBe('rejected');
    expect($revision->approved_by)->toBe($moderator->id);
    expect($revision->rejection_reason)->toBe('Content does not meet quality standards');

    // Guide should not be updated
    $this->guide->refresh();
    expect($this->guide->title)->not->toBe($revision->title);
});

it('validates rejection reason is required', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    actingAs($moderator)
        ->post(route('revisions.reject', $revision), [])
        ->assertSessionHasErrors(['rejection_reason']);
});

it('lists pending revisions on moderation page', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision1 = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    $revision2 = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'approved',
    ]);

    actingAs($moderator)
        ->get(route('revisions.index'))
        ->assertInertia(fn ($page) => $page
            ->component('Revisions/Index')
            ->has('revisions.data', 1) // Only pending revisions
            ->where('revisions.data.0.id', $revision1->id)
        );
});

it('shows revision details on show page', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);

    actingAs($moderator)
        ->get(route('revisions.show', $revision))
        ->assertInertia(fn ($page) => $page
            ->component('Revisions/Show')
            ->where('revision.id', $revision->id)
            ->where('guide.id', $this->guide->id)
        );
});

it('applies all revision fields when approving', function () {
    $moderator = User::factory()->create(['is_admin' => true]);

    $revision = GuideRevision::factory()->create([
        'guide_id' => $this->guide->id,
        'user_id' => $this->user->id,
        'title' => 'New Title',
        'tldr' => 'New TLDR',
        'content' => '## New Content',
        'difficulty' => 'advanced',
        'estimated_minutes' => 30,
        'status' => 'pending',
    ]);

    actingAs($moderator)
        ->post(route('revisions.approve', $revision));

    $this->guide->refresh();
    expect($this->guide->title)->toBe('New Title');
    expect($this->guide->tldr)->toBe('New TLDR');
    expect($this->guide->content)->toBe('## New Content');
    expect($this->guide->difficulty->value)->toBe('advanced');
    expect($this->guide->estimated_minutes)->toBe(30);
});
