<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\AutoLogout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auto_logout' => AutoLogout::class,
            'auth' => AuthMiddleware::class,
            // Add other middleware aliases as needed
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Define exception handling as needed
    })
    ->create();
