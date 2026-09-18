<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// CRITICAL: Trust proxies BEFORE Laravel touches the request
if (getenv('APP_ENV') === 'production' || getenv('RENDER')) {
    \Illuminate\Http\Request::setTrustedProxies(
        ['*'],
        \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB
    );
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $request = Request::capture();
    // Ensure proxies are still set
    if (getenv('APP_ENV') === 'production' || getenv('RENDER')) {
        \Illuminate\Http\Request::setTrustedProxies(
            ['*'],
            \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB
        );
    }
    $app->handleRequest($request);
} catch (Throwable $e) {
    error_log("Fatal error in index.php: " . $e->getMessage());
    throw $e;
}
