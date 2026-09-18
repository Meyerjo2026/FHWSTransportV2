<?php

/**
 * Trust proxies for PaaS environments (Render, Heroku, AWS, etc.)
 * Must run BEFORE any request validation.
 */

use Illuminate\Http\Request;

// For environments where the reverse proxy terminates TLS and forwards plain HTTP
if (in_array(getenv('APP_ENV'), ['production', 'staging']) || getenv('RENDER')) {
    Request::setTrustedProxies(
        ['*'],
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB
    );
}
