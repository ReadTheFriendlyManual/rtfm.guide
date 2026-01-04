<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Custom Email Verification Routes
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware(['auth'])->name('verification.notice');

Route::post('/email/verification-notification', [App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth'])
    ->name('verification.send');

Route::get('/email/verify/{token}', App\Http\Controllers\Auth\EmailVerificationController::class)
    ->name('verification.verify');

// Public Routes
Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

Route::get('/guides', [App\Http\Controllers\GuideController::class, 'index'])->name('guides.index');

Route::get('/categories', function () {
    return response('Categories coming soon...', 200);
})->name('categories.index');

Route::get('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public API Routes
Route::get('/api/search/quick', [App\Http\Controllers\Api\SearchController::class, 'quick'])->name('api.search.quick');

// Share links
Route::get('/share/{token}', [App\Http\Controllers\ShareLinkController::class, 'show'])->name('share.show');
Route::post('/api/guides/{guide}/share-links', [App\Http\Controllers\ShareLinkController::class, 'store'])->name('api.guides.share-links.store');

// Newsletter
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/verify', [App\Http\Controllers\NewsletterController::class, 'verify'])->name('newsletter.verify');
Route::get('/newsletter/unsubscribe/{token}', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// OG Images
Route::get('/og-images/guide/{guide}', [App\Http\Controllers\OgImageController::class, 'guide'])->name('og-images.guide');
Route::get('/og-images/category/{category}', [App\Http\Controllers\OgImageController::class, 'category'])->name('og-images.category');
Route::get('/og-images/user/{user}', [App\Http\Controllers\OgImageController::class, 'user'])
    ->middleware('auth')
    ->name('og-images.user');

Route::middleware(['auth'])->group(function () {
    // API Routes for preferences
    Route::post('/api/preferences/mode', [App\Http\Controllers\Api\PreferencesController::class, 'updateMode'])->name('api.preferences.mode');
    Route::post('/api/preferences/theme', [App\Http\Controllers\Api\PreferencesController::class, 'updateTheme'])->name('api.preferences.theme');

    // API Routes for saved guides
    Route::post('/api/guides/{guide}/save', [App\Http\Controllers\Api\SavedGuideController::class, 'store'])->name('api.guides.save');
    Route::delete('/api/guides/{guide}/save', [App\Http\Controllers\Api\SavedGuideController::class, 'destroy'])->name('api.guides.unsave');

    // API Routes for reactions
    Route::post('/api/guides/{guide}/reactions', [App\Http\Controllers\Api\ReactionController::class, 'store'])->name('api.guides.reactions.store');
    Route::delete('/api/guides/{guide}/reactions', [App\Http\Controllers\Api\ReactionController::class, 'destroy'])->name('api.guides.reactions.destroy');

    // API Routes for comments
    Route::post('/api/guides/{guide}/comments', [App\Http\Controllers\Api\CommentController::class, 'store'])->name('api.guides.comments.store');
    Route::put('/api/comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'update'])->name('api.comments.update');
    Route::delete('/api/comments/{comment}', [App\Http\Controllers\Api\CommentController::class, 'destroy'])->name('api.comments.destroy');

    // API Routes for content flags
    Route::post('/api/comments/{comment}/flag', [App\Http\Controllers\Api\ContentFlagController::class, 'flagComment'])->name('api.comments.flag');
    Route::delete('/api/comments/{comment}/flag', [App\Http\Controllers\Api\ContentFlagController::class, 'unflagComment'])->name('api.comments.unflag');

    // API Routes for notifications
    Route::get('/api/notifications', [App\Http\Controllers\Api\NotificationController::class, 'index'])->name('api.notifications.index');
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\Api\NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::post('/api/notifications/{id}/read', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->name('api.notifications.read');
    Route::post('/api/notifications/read-all', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead'])->name('api.notifications.read-all');
    Route::delete('/api/notifications/{id}', [App\Http\Controllers\Api\NotificationController::class, 'destroy'])->name('api.notifications.destroy');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', function () {
        return response('Profile settings coming soon...', 200);
    })->name('profile.edit');

    Route::get('settings/password', function () {
        return response('Password settings coming soon...', 200);
    })->name('user-password.edit');

    Route::get('settings/appearance', function () {
        return response('Appearance settings coming soon...', 200);
    })->name('appearance.edit');

    Route::get('settings/two-factor', function () {
        return response('Two-factor settings coming soon...', 200);
    })
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('/profile/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');

    Route::get('/my-guides', [App\Http\Controllers\GuideManagementController::class, 'index'])->name('guides.my');

    Route::get('/saved-guides', [App\Http\Controllers\SavedGuidePageController::class, 'index'])->name('guides.saved');

    Route::get('/guides/create', [App\Http\Controllers\GuideManagementController::class, 'create'])->name('guides.create');
    Route::post('/guides', [App\Http\Controllers\GuideManagementController::class, 'store'])->name('guides.store');

    Route::get('/guides/{guide}/edit', [App\Http\Controllers\GuideManagementController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [App\Http\Controllers\GuideManagementController::class, 'update'])->name('guides.update');

    // Guide revisions (moderation)
    Route::get('/revisions', [App\Http\Controllers\GuideRevisionController::class, 'index'])->name('revisions.index');
    Route::get('/revisions/{revision}', [App\Http\Controllers\GuideRevisionController::class, 'show'])->name('revisions.show');
    Route::post('/revisions/{revision}/approve', [App\Http\Controllers\GuideRevisionController::class, 'approve'])->name('revisions.approve');
    Route::post('/revisions/{revision}/reject', [App\Http\Controllers\GuideRevisionController::class, 'reject'])->name('revisions.reject');
});

// Public guide view - must be after authenticated routes to avoid conflicts
Route::get('/guides/{guide}', [App\Http\Controllers\GuideController::class, 'show'])->name('guides.show');

// Category landing pages - must be last to act as catch-all
Route::get('/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.landing');
