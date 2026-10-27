<?php

return [
    /*
     * VKONNECT CORS — allow the SPA to call the API. Origins come from
     * CORS_ALLOWED_ORIGINS (comma-separated) in every environment; the
     * localhost defaults below only apply when that env var is unset AND
     * APP_ENV is local, so staging/production never fall back to them.
     */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => env('CORS_ALLOWED_ORIGINS')
        ? array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS')))
        : (env('APP_ENV') === 'local' ? [
            'http://localhost:5173',
            'http://localhost:5174',
            'http://127.0.0.1:5173',
        ] : []),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * No credentials cross-origin. The API authenticates on a bearer token in
     * the Authorization header, and the frontend no longer sets
     * withCredentials -- nothing reads a cookie, so nothing needs to send one.
     *
     * Leaving this on is not free: with credentials allowed the spec forbids a
     * wildcard origin, and any cookie the browser happens to hold for this
     * host would ride along on every cross-origin request for no one to read.
     */
    'supports_credentials' => false,
];
