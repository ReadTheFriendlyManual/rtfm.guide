<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Public Routes
Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

Route::get('/guides', [App\Http\Controllers\GuideController::class, 'index'])->name('guides.index');

Route::get('/categories', function () {
    return response('Categories coming soon...', 200);
})->name('categories.index');

Route::get('/categories/{category}', function () {
    return response('Category detail coming soon...', 200);
})->name('categories.show');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public API Routes
Route::get('/api/search/quick', [App\Http\Controllers\Api\SearchController::class, 'quick'])->name('api.search.quick');

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

    Route::get('/profile/{user}', function () {
        return response('User profile coming soon...', 200);
    })->name('users.show');

    Route::get('/my-guides', [App\Http\Controllers\GuideManagementController::class, 'index'])->name('guides.my');

    Route::get('/saved-guides', [App\Http\Controllers\SavedGuidePageController::class, 'index'])->name('guides.saved');

    Route::get('/guides/create', [App\Http\Controllers\GuideManagementController::class, 'create'])->name('guides.create');
    Route::post('/guides', [App\Http\Controllers\GuideManagementController::class, 'store'])->name('guides.store');

    Route::get('/guides/{guide}/edit', [App\Http\Controllers\GuideManagementController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [App\Http\Controllers\GuideManagementController::class, 'update'])->name('guides.update');
});

// Public guide view - must be after authenticated routes to avoid conflicts
Route::get('/guides/{guide}', [App\Http\Controllers\GuideController::class, 'show'])->name('guides.show');
