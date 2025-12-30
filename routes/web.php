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

Route::middleware(['auth'])->group(function () {
    // Settings routes (basic access, no verification required)
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
});

// Routes that require email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // User profile and guides
    Route::get('/profile/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('users.show');
    Route::get('/my-guides', [App\Http\Controllers\GuideManagementController::class, 'index'])->name('guides.my');
    Route::get('/saved-guides', [App\Http\Controllers\SavedGuidePageController::class, 'index'])->name('guides.saved');

    // Guide management (create and edit)
    Route::get('/guides/create', [App\Http\Controllers\GuideManagementController::class, 'create'])->name('guides.create');
    Route::post('/guides', [App\Http\Controllers\GuideManagementController::class, 'store'])->name('guides.store');
    Route::get('/guides/{guide}/edit', [App\Http\Controllers\GuideManagementController::class, 'edit'])->name('guides.edit');
    Route::put('/guides/{guide}', [App\Http\Controllers\GuideManagementController::class, 'update'])->name('guides.update');
});

// Public guide view - must be after authenticated routes to avoid conflicts
Route::get('/guides/{guide}', [App\Http\Controllers\GuideController::class, 'show'])->name('guides.show');
