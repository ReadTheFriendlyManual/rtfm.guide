<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ModeController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Public Routes
Route::get('/', [LandingController::class, 'show'])->name('home');
Route::post('/mode', ModeController::class)->name('mode.update');
Route::post('/subscribe', [LandingController::class, 'subscribe'])->name('subscribe');

Route::get('/guides', App\Livewire\Guides\Index::class)->name('guides.index');

Route::get('/guides/{guide}', App\Livewire\Guides\Show::class)->name('guides.show');

Route::get('/categories', App\Livewire\Categories\Index::class)->name('categories.index');

Route::get('/categories/{category}', App\Livewire\Categories\Show::class)->name('categories.show');

Route::get('/search', App\Livewire\Search\Index::class)->name('search.index');

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

});
