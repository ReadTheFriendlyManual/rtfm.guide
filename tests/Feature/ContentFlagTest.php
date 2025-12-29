<?php

use App\Enums\FlagReason;
use App\Models\Comment;
use App\Models\ContentFlag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can flag a comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->actingAs($user)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::Spam->value,
            'description' => 'This is spam',
        ])
        ->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Comment has been flagged for review.',
        ]);

    $this->assertDatabaseHas('content_flags', [
        'user_id' => $user->id,
        'flaggable_id' => $comment->id,
        'flaggable_type' => Comment::class,
        'reason' => FlagReason::Spam->value,
        'status' => 'pending',
    ]);
});

test('users cannot flag their own comments', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'is_approved' => true,
    ]);

    $this->actingAs($user)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::Spam->value,
        ])
        ->assertStatus(422)
        ->assertJson([
            'message' => 'You cannot flag your own comment.',
        ]);

    $this->assertDatabaseMissing('content_flags', [
        'user_id' => $user->id,
        'flaggable_id' => $comment->id,
    ]);
});

test('users cannot flag the same comment twice', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    // First flag
    ContentFlag::create([
        'user_id' => $user->id,
        'flaggable_id' => $comment->id,
        'flaggable_type' => Comment::class,
        'reason' => FlagReason::Spam->value,
        'status' => 'pending',
    ]);

    // Attempt second flag
    $this->actingAs($user)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::OffensiveContent->value,
        ])
        ->assertStatus(422)
        ->assertJson([
            'message' => 'You have already flagged this comment.',
        ]);

    // Should still only have one flag
    expect(ContentFlag::where('user_id', $user->id)->count())->toBe(1);
});

test('flag requires valid reason', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->actingAs($user)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => 'invalid_reason',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('reason');
});

test('description is optional when flagging', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->actingAs($user)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::Other->value,
        ])
        ->assertCreated();

    $flag = ContentFlag::first();
    expect($flag->description)->toBeNull();
});

test('user can remove their own pending flag', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $flag = ContentFlag::create([
        'user_id' => $user->id,
        'flaggable_id' => $comment->id,
        'flaggable_type' => Comment::class,
        'reason' => FlagReason::Spam->value,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->deleteJson("/api/comments/{$comment->id}/flag")
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Flag removed successfully.',
        ]);

    $this->assertDatabaseMissing('content_flags', [
        'id' => $flag->id,
    ]);
});

test('user cannot remove flag that does not exist', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->actingAs($user)
        ->deleteJson("/api/comments/{$comment->id}/flag")
        ->assertStatus(404)
        ->assertJson([
            'message' => 'You have not flagged this comment.',
        ]);
});

test('user cannot remove reviewed flag', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    ContentFlag::create([
        'user_id' => $user->id,
        'flaggable_id' => $comment->id,
        'flaggable_type' => Comment::class,
        'reason' => FlagReason::Spam->value,
        'status' => 'reviewed',
    ]);

    $this->actingAs($user)
        ->deleteJson("/api/comments/{$comment->id}/flag")
        ->assertStatus(422)
        ->assertJson([
            'message' => 'Cannot remove flag that has already been reviewed.',
        ]);
});

test('guest cannot flag a comment', function () {
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->postJson("/api/comments/{$comment->id}/flag", [
        'reason' => FlagReason::Spam->value,
    ])
        ->assertStatus(401);
});

test('multiple users can flag the same comment', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => true]);

    $this->actingAs($user1)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::Spam->value,
        ])
        ->assertCreated();

    $this->actingAs($user2)
        ->postJson("/api/comments/{$comment->id}/flag", [
            'reason' => FlagReason::OffensiveContent->value,
        ])
        ->assertCreated();

    expect(ContentFlag::where('flaggable_id', $comment->id)->count())->toBe(2);
});
