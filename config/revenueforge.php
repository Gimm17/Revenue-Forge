<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RevenueForge Application Configuration
    |--------------------------------------------------------------------------
    |
    | These values configure the core behavior of the RevenueForge application.
    |
    */

    'default_currency' => env('REVENUEFORGE_DEFAULT_CURRENCY', 'IDR'),

    'trial_days' => env('REVENUEFORGE_TRIAL_DAYS', 7),

    'affiliate_cookie_days' => env('REVENUEFORGE_AFFILIATE_COOKIE_DAYS', 30),

    'enable_credits' => env('REVENUEFORGE_ENABLE_CREDITS', true),

    'enable_affiliates' => env('REVENUEFORGE_ENABLE_AFFILIATES', true),

    'enable_portal_magic_link' => env('REVENUEFORGE_ENABLE_PORTAL_MAGIC_LINK', true),

    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    */

    'ai' => [
        'provider' => env('AI_PROVIDER', 'openai_compatible'),
        'base_url' => env('AI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai'),
        'api_key' => env('AI_API_KEY'),
        'model' => env('AI_MODEL', 'gemini-2.0-flash'),
        'max_tokens' => env('AI_MAX_TOKENS', 2000),
        'temperature' => env('AI_TEMPERATURE', 0.7),
    ],
];
