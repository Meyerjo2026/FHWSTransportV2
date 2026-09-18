<?php

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;

if (config('trustedproxies.proxies') === '*') {
    Request::setTrustedProxies(
        ['*'],
        config('trustedproxies.headers')
    );
}
