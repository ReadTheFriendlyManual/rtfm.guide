<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GuideController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/guides', [GuideController::class, 'index'])->name('api.guides.index');
    Route::get('/guides/trending', [GuideController::class, 'trending'])->name('api.guides.trending');
    Route::get('/guides/random', [GuideController::class, 'random'])->name('api.guides.random');
    Route::get('/guides/{guide:slug}', [GuideController::class, 'show'])->name('api.guides.show');

    Route::get('/categories', [CategoryController::class, 'index'])->name('api.categories.index');
    Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('api.categories.show');
});
