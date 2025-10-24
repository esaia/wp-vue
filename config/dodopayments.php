<?php


return [
    'api_key' => env('DODO_PAYMENTS_API_KEY'),
    'environment' => env('DODO_PAYMENTS_ENVIRONMENT', 'test_mode'),
    'base_url' => env('DODO_PAYMENTS_BASE_URL', 'https://test.dodopayments.com'),
    'webhook_secret' => env('DODO_PAYMENTS_WEBHOOK_SECRET'),
];
