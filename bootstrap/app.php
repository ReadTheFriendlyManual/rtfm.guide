<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PersistContentMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Inertia\ServiceProvider as InertiaServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        InertiaServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', PersistContentMode::class);
        $middleware->appendToGroup('web', HandleInertiaRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
