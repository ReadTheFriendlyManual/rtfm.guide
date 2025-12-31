<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuideImportController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/guide-imports', [GuideImportController::class, 'upload'])->name('api.guide-imports.upload');
});
