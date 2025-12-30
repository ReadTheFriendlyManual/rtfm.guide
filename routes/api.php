<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// V1 API Routes
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Public API Routes
    Route::get('/search/quick', [App\Http\Controllers\Api\SearchController::class, 'quick'])->name('search.quick');

    // Authenticated API Routes
    Route::middleware('auth:sanctum')->group(function () {
        // User info
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user');

        // Preferences (no verification required)
        Route::post('/preferences/mode', [App\Http\Controllers\Api\PreferencesController::class, 'updateMode'])->name('preferences.mode');
        Route::post('/preferences/theme', [App\Http\Controllers\Api\PreferencesController::class, 'updateTheme'])->name('preferences.theme');

        // Email verified routes
        Route::middleware('verified')->group(function () {
            // Saved Guides
            Route::post('/guides/{guide}/save', [App\Http\Controllers\Api\SavedGuideController::class, 'store'])->name('guides.save');
            Route::delete('/guides/{guide}/save', [App\Http\Controllers\Api\SavedGuideController::class, 'destroy'])->name('guides.unsave');

            // Reactions
            Route::post('/guides/{guide}/reactions', [App\Http\Controllers\Api\ReactionController::class, 'store'])->name('guides.reactions.store');
            Route::delete('/guides/{guide}/reactions', [App\Http\Controllers\Api\ReactionController::class, 'destroy'])->name('guides.reactions.destroy');

            // Comments
            Route::post('/guides/{guide}/comments', [App\Http\Controllers\Api\CommentController::class, 'store'])->name('guides.comments.store');
            Route::put('/comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'update'])->name('comments.update');
            Route::delete('/comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'destroy'])->name('comments.destroy');

            // Content Flags
            Route::post('/comments/{comment}/flag', [App\Http\Controllers\Api\ContentFlagController::class, 'flagComment'])->name('comments.flag');
            Route::delete('/comments/{comment}/flag', [App\Http\Controllers\Api\ContentFlagController::class, 'unflagComment'])->name('comments.unflag');

            // Notifications
            Route::get('/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index'])->name('notifications.index');
            Route::get('/notifications/unread-count', [App\Http\Controllers\Api\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
            Route::post('/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->name('notifications.read');
            Route::post('/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
            Route::delete('/notifications/{id}', [App\Http\Controllers\Api\NotificationController::class, 'destroy'])->name('notifications.destroy');
        });
    });
});
