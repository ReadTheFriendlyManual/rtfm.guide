<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Inertia\Inertia;

// Public Routes
Route::get('/', function () {
    return Inertia::render('Public/Home');
})->name('home');

Route::get('/guides', function () {
    return response('Guides coming soon...', 200);
})->name('guides.index');

Route::get('/guides/{guide}', function () {
    return response('Guide detail coming soon...', 200);
})->name('guides.show');

Route::get('/categories', function () {
    return response('Categories coming soon...', 200);
})->name('categories.index');

Route::get('/categories/{category}', function () {
    return response('Category detail coming soon...', 200);
})->name('categories.show');

Route::get('/search', function () {
    return response('Search coming soon...', 200);
})->name('search.index');

Route::get('/dashboard', function () {
    return response('Dashboard coming soon...', 200);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
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

    Route::get('/my-guides', function () {
        return response('My guides coming soon...', 200);
    })->name('guides.my');

    Route::get('/saved-guides', function () {
        return response('Saved guides coming soon...', 200);
    })->name('guides.saved');

    Route::get('/guides/create', function () {
        return response('Create guide coming soon...', 200);
    })->name('guides.create');

    Route::get('/guides/{guide}/edit', function () {
        return response('Edit guide coming soon...', 200);
    })->name('guides.edit');
});
