<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware per comodità
        $middleware->alias([
            'frontend' => EnsureFrontendRequestsAreStateful::class,
        ]);

        // Se vuoi applicarlo a TUTTE le richieste API:
        $middleware->prependToGroup('api', 'frontend');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
