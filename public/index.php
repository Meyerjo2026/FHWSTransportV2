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

// Normalise empty REMOTE_ADDR — Render's load balancer can deliver requests
// with REMOTE_ADDR='' which causes Symfony IpUtils::checkIp4() to crash.
if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '') {
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}

// Trust proxies BEFORE Laravel touches the request.
// Use explicit CIDRs — never '*', which can produce null CIDR entries internally.
if (getenv('APP_ENV') === 'production' || getenv('APP_ENV') === 'staging' || getenv('RENDER')) {
    \Illuminate\Http\Request::setTrustedProxies(
        ['127.0.0.1', '10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16'],
        \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
        \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB
    );
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
