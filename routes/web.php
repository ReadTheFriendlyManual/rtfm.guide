<?php

use App\Livewire\Pages\ComingSoon;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Public Routes
Route::get('/', ComingSoon::class)->name('home');

Route::get('/guides', function () {
    return view('guides.index');
})->name('guides.index');

Route::get('/guides/{guide}', function () {
    return view('guides.show');
})->name('guides.show');

Route::get('/categories', function () {
    return view('categories.index');
})->name('categories.index');

Route::get('/categories/{category}', function () {
    return view('categories.show');
})->name('categories.show');

Route::get('/search', function () {
    return view('search.index');
})->name('search.index');

// Temporarily redirect to coming soon until we build the pages
Route::get('/guides', ComingSoon::class)->name('guides.index');
Route::get('/categories', ComingSoon::class)->name('categories.index');
Route::get('/search', ComingSoon::class)->name('search.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // User Profile and Guides
    Route::get('/profile/{user}', function () {
        return view('users.show');
    })->name('users.show');

    Route::get('/my-guides', function () {
        return view('guides.my');
    })->name('guides.my');

    Route::get('/saved-guides', function () {
        return view('guides.saved');
    })->name('guides.saved');

    // Guide Management (authenticated)
    Route::get('/guides/create', function () {
        return view('guides.create');
    })->name('guides.create');

    Route::get('/guides/{guide}/edit', function () {
        return view('guides.edit');
    })->name('guides.edit');

    // Temporarily redirect to coming soon
    Route::get('/profile/{user}', ComingSoon::class)->name('users.show');
    Route::get('/my-guides', ComingSoon::class)->name('guides.my');
    Route::get('/saved-guides', ComingSoon::class)->name('guides.saved');
    Route::get('/guides/create', ComingSoon::class)->name('guides.create');
});
