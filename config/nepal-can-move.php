<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Nepal Can Move API Token
    |--------------------------------------------------------------------------
    |
    | The API token for Nepal Can Move. You can also set this in your
    | .env file as NCM_TOKEN.
    |
    */
    'token' => env('NCM_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Sandbox/Live Mode
    |--------------------------------------------------------------------------
    |
    | Set to true to use the Nepal Can Move sandbox environment. You can also
    | set this in your .env file as NCM_SANDBOX_MODE.
    |
    */
    'sandbox_mode' => env('NCM_SANDBOX_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Nepal Can Move Base URLs
    |--------------------------------------------------------------------------
    |
    | The base URLs for Nepal Can Move API.
    |
    */
    'base_urls' => [
        'live' => env('NCM_LIVE_BASE_URL', 'https://portal.nepalcanmove.com/api'),
        'demo' => env('NCM_DEMO_BASE_URL', 'https://demo.nepalcanmove.com/api'),
    ],
];
