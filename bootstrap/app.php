<?php

// CRITICAL: Trust proxies before any request handling
require __DIR__.'/proxies.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render (like most PaaS providers) terminates TLS at its edge and
        // forwards plain HTTP to the container, so Laravel must trust that
        // proxy to know the original request was HTTPS — otherwise it
        // generates http:// URLs and secure-cookie/session behaviour breaks.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
            'password.changed' => \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
