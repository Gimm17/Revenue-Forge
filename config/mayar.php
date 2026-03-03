<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mayar API Configuration
    |--------------------------------------------------------------------------
    |
    | These values configure the connection to the Mayar payment gateway.
    |
    */

    'base_url' => env('MAYAR_BASE_URL', 'https://api.mayar.id/hl/v1'),

    'api_key' => env('MAYAR_API_KEY'),

    'webhook_secret' => env('MAYAR_WEBHOOK_SECRET'),

    'portal_subdomain' => env('MAYAR_PORTAL_SUBDOMAIN'),

    'mock_checkout' => env('MAYAR_MOCK_CHECKOUT', false),

    'timeout' => env('MAYAR_TIMEOUT', 30),
];
