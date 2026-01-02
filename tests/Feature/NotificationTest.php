<?php

use App\Models\Comment;
use App\Models\Guide;
use App\Models\User;
use App\Notifications\CommentReplyNotification;
use Illuminate\Support\Facades\Notification;

test('notification is sent when someone replies to a comment', function () {
    Notification::fake();

    $user = User::factory()->create();
    $commenter = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // User creates a comment
    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // Another user replies to the comment
    $this->actingAs($commenter)
        ->postJson("/api/guides/{$guide->id}/comments", [
            'content' => 'This is a reply',
            'parent_id' => $parentComment->id,
        ])
        ->assertCreated();

    // Assert notification was sent
    Notification::assertSentTo(
        $user,
        CommentReplyNotification::class,
        function ($notification, $channels) use ($parentComment, $guide) {
            $data = $notification->toArray(new User);

            return $data['parent_comment_id'] === $parentComment->id
                && $data['guide_id'] === $guide->id;
        }
    );
});

test('notification is not sent when replying to own comment', function () {
    Notification::fake();

    $user = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // User creates a comment
    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    // Same user replies to their own comment
    $this->actingAs($user)
        ->postJson("/api/guides/{$guide->id}/comments", [
            'content' => 'Replying to myself',
            'parent_id' => $parentComment->id,
        ])
        ->assertCreated();

    // No notification should be sent
    Notification::assertNothingSent();
});

test('user can fetch their notifications', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    // Manually create notification
    $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $response = $this->actingAs($user)
        ->getJson('/api/notifications')
        ->assertSuccessful();

    expect($response->json('notifications'))->toHaveCount(1);
    expect($response->json('unread_count'))->toBe(1);
});

test('user can get unread notification count', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $this->actingAs($user)
        ->getJson('/api/notifications/unread-count')
        ->assertSuccessful()
        ->assertJson(['count' => 1]);
});

test('user can mark notification as read', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $notification = $user->unreadNotifications->first();

    $this->actingAs($user)
        ->postJson("/api/notifications/{$notification->id}/read")
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});

test('user can mark all notifications as read', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create multiple notifications
    for ($i = 0; $i < 3; $i++) {
        $parentComment = Comment::factory()->create([
            'user_id' => $user->id,
            'guide_id' => $guide->id,
            'is_approved' => true,
        ]);

        $reply = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'guide_id' => $guide->id,
            'parent_id' => $parentComment->id,
            'is_approved' => true,
        ]);

        $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));
    }

    expect($user->unreadNotifications)->toHaveCount(3);

    $this->actingAs($user)
        ->postJson('/api/notifications/read-all')
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});

test('user can delete a notification', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $notification = $user->notifications->first();

    $this->actingAs($user)
        ->deleteJson("/api/notifications/{$notification->id}")
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);

    expect($user->fresh()->notifications)->toHaveCount(0);
});

test('user cannot mark another users notification as read', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user1->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    $user1->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $notification = $user1->notifications->first();

    $this->actingAs($user2)
        ->postJson("/api/notifications/{$notification->id}/read")
        ->assertNotFound();
});

test('user cannot delete another users notification', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user1->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $otherUser->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
    ]);

    $user1->notify(new CommentReplyNotification($reply, $parentComment, $guide));

    $notification = $user1->notifications->first();

    $this->actingAs($user2)
        ->deleteJson("/api/notifications/{$notification->id}")
        ->assertNotFound();

    expect($user1->fresh()->notifications)->toHaveCount(1);
});

test('guest cannot access notifications', function () {
    $this->getJson('/api/notifications')
        ->assertStatus(401);

    $this->getJson('/api/notifications/unread-count')
        ->assertStatus(401);
});

test('notifications are paginated', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    // Create 20 notifications
    for ($i = 0; $i < 20; $i++) {
        $parentComment = Comment::factory()->create([
            'user_id' => $user->id,
            'guide_id' => $guide->id,
            'is_approved' => true,
        ]);

        $reply = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'guide_id' => $guide->id,
            'parent_id' => $parentComment->id,
            'is_approved' => true,
        ]);

        $user->notify(new CommentReplyNotification($reply, $parentComment, $guide));
    }

    $response = $this->actingAs($user)
        ->getJson('/api/notifications?per_page=10')
        ->assertSuccessful();

    expect($response->json('notifications'))->toHaveCount(10);
    expect($response->json('pagination.total'))->toBe(20);
    expect($response->json('pagination.last_page'))->toBe(2);
});

test('comment reply notification sends email with custom template', function () {
    $user = User::factory()->create();
    $commenter = User::factory()->create();
    $guide = Guide::factory()->published()->create();

    $parentComment = Comment::factory()->create([
        'user_id' => $user->id,
        'guide_id' => $guide->id,
        'is_approved' => true,
        'content' => 'This is my original comment',
    ]);

    $reply = Comment::factory()->create([
        'user_id' => $commenter->id,
        'guide_id' => $guide->id,
        'parent_id' => $parentComment->id,
        'is_approved' => true,
        'content' => 'This is a reply to your comment',
    ]);

    $notification = new CommentReplyNotification($reply, $parentComment, $guide);
    $mail = $notification->toMail($user);

    expect($mail->subject)->toBe('New Reply to Your Comment - RTFM.guide');
    expect($mail->viewData)->toHaveKey('url');
    expect($mail->viewData)->toHaveKey('replyUserName');
    expect($mail->viewData)->toHaveKey('replyContent');
    expect($mail->viewData)->toHaveKey('guideTitle');
    expect($mail->viewData['replyUserName'])->toBe($commenter->name);
    expect($mail->viewData['replyContent'])->toBe('This is a reply to your comment');
    expect($mail->viewData['guideTitle'])->toBe($guide->title);
    expect($mail->viewData['url'])->toContain($guide->slug);
    expect($mail->viewData['url'])->toContain('#comment-'.$reply->id);
});
