<?php

/**
 * Trust proxies for PaaS environments (Render, Heroku, AWS, etc.)
 * Must run BEFORE any request validation.
 */

use Illuminate\Http\Request;

// Normalise empty REMOTE_ADDR — Render's load balancer can leave it blank,
// which causes Symfony's IpUtils::checkIp4() to crash with a TypeError.
if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '') {
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}

// For environments where the reverse proxy terminates TLS and forwards plain HTTP.
// Use explicit CIDRs instead of '*' to avoid a null CIDR edge-case in Symfony IpUtils.
if (in_array(getenv('APP_ENV'), ['production', 'staging']) || getenv('RENDER')) {
    Request::setTrustedProxies(
        ['127.0.0.1', '10.0.0.0/8', '172.16.0.0/12', '192.168.0.0/16'],
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB
    );
}
