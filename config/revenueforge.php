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
        'providers' => [
            [
                'name'       => 'zhipu',
                'base_url'   => env('AI_ZHIPU_BASE_URL', 'https://api.z.ai/api/coding/paas/v4'),
                'api_key'    => env('AI_ZHIPU_API_KEY'),
                'model'      => env('AI_ZHIPU_MODEL', 'glm-4.6'),
                'max_tokens' => (int) env('AI_MAX_TOKENS', 5000),
                'temperature'=> (float) env('AI_TEMPERATURE', 0.7),
            ],
            [
                'name'       => 'kimi',
                'base_url'   => env('AI_KIMI_BASE_URL', 'https://api.moonshot.cn/v1'),
                'api_key'    => env('AI_KIMI_API_KEY'),
                'model'      => env('AI_KIMI_MODEL', 'moonshot-v1-8k'),
                'max_tokens' => (int) env('AI_MAX_TOKENS', 5000),
                'temperature'=> (float) env('AI_TEMPERATURE', 0.7),
            ],
            [
                'name'       => 'gemini',
                'base_url'   => env('AI_GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai'),
                'api_key'    => env('AI_GEMINI_API_KEY'),
                'model'      => env('AI_GEMINI_MODEL', 'gemini-2.0-flash'),
                'max_tokens' => (int) env('AI_MAX_TOKENS', 5000),
                'temperature'=> (float) env('AI_TEMPERATURE', 0.7),
            ],
        ],
    ],
];
