<?php

use App\Models\Comment;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('new user first comment requires approval', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $this->actingAs($user)
        ->postJson("/api/guides/{$guide->id}/comments", [
            'content' => 'This is my first comment',
        ])
        ->assertCreated();

    $comment = Comment::first();
    expect($comment->is_approved)->toBeFalse();
});

test('user with approved comment gets auto-approved', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create an approved comment for this user
    Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // New comment should be auto-approved
    $this->actingAs($user)
        ->postJson("/api/guides/{$guide->id}/comments", [
            'content' => 'This is my second comment',
        ])
        ->assertCreated();

    $newComment = Comment::latest()->first();
    expect($newComment->is_approved)->toBeTrue();
});

test('only approved comments are visible to guests', function () {
    $guide = Guide::factory()->published()->create();

    // Create approved comment
    $approvedComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'is_approved' => true,
        'content' => 'Approved comment',
    ]);

    // Create pending comment
    $pendingComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'is_approved' => false,
        'content' => 'Pending comment',
    ]);

    $response = $this->get("/guides/{$guide->slug}");

    $comments = $response->viewData('page')['props']['comments'];
    expect($comments)->toHaveCount(1);
    expect($comments[0]['id'])->toBe($approvedComment->id);
});

test('users can see their own pending comments', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create user's pending comment
    $userComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'user_id' => $user->id,
        'is_approved' => false,
        'content' => 'My pending comment',
    ]);

    // Create another user's pending comment
    $otherComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'is_approved' => false,
        'content' => 'Other pending comment',
    ]);

    $response = $this->actingAs($user)->get("/guides/{$guide->slug}");

    $comments = $response->viewData('page')['props']['comments'];
    expect($comments)->toHaveCount(1);
    expect($comments[0]['id'])->toBe($userComment->id);
});

test('approved comment replies are auto-approved for returning users', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create approved parent comment
    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // Reply should be auto-approved
    $response = $this->actingAs($user)
        ->postJson("/api/guides/{$guide->id}/comments", [
            'content' => 'This is my reply',
            'parent_id' => $parentComment->id,
        ])
        ->assertCreated();

    $reply = Comment::where('parent_id', $parentComment->id)->first();
    expect($reply)->not->toBeNull();
    expect($reply->is_approved)->toBeTrue();
    expect($reply->parent_id)->toBe($parentComment->id);
});

test('only approved replies are shown to guests', function () {
    $guide = Guide::factory()->published()->create();

    // Create approved parent comment
    $parentComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // Create approved reply
    $approvedReply = Comment::factory()->create([
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
        'content' => 'Approved reply',
    ]);

    // Create pending reply
    $pendingReply = Comment::factory()->create([
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => false,
        'content' => 'Pending reply',
    ]);

    $response = $this->get("/guides/{$guide->slug}");

    $comments = $response->viewData('page')['props']['comments'];
    expect($comments[0]['replies'])->toHaveCount(1);
    expect($comments[0]['replies'][0]['id'])->toBe($approvedReply->id);
});

test('users can see their own pending replies', function () {
    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create approved parent comment
    $parentComment = Comment::factory()->create([
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // Create user's pending reply
    $userReply = Comment::factory()->create([
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'user_id' => $user->id,
        'is_approved' => false,
        'content' => 'My pending reply',
    ]);

    // Create another user's pending reply
    $otherReply = Comment::factory()->create([
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => false,
        'content' => 'Other pending reply',
    ]);

    $response = $this->actingAs($user)->get("/guides/{$guide->slug}");

    $comments = $response->viewData('page')['props']['comments'];
    expect($comments[0]['replies'])->toHaveCount(1);
    expect($comments[0]['replies'][0]['id'])->toBe($userReply->id);
});

test('hasApprovedComment method works correctly', function () {
    $user = User::factory()->create();
    expect($user->hasApprovedComment())->toBeFalse();

    Comment::factory()->create([
        'user_id' => $user->id,
        'is_approved' => false,
    ]);
    expect($user->hasApprovedComment())->toBeFalse();

    Comment::factory()->create([
        'user_id' => $user->id,
        'is_approved' => true,
    ]);
    expect($user->hasApprovedComment())->toBeTrue();
});
