<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'payment' => [
        'trc20_address' => env('PAYMENT_USDT_TRC20_ADDRESS', 'TQG2Ry4k9N9tF1dYR1T9Hs1H4stDZ8mtyi'),
    ],

    'telegram' => [
        'username' => env('TELEGRAM_USERNAME', 'Binance_Balance_4U'),
        'bot_url' => env('TELEGRAM_BOT_URL', 'https://t.me/Binance_Balance_4U'),
    ],

    'admin' => [
        'password' => env('ADMIN_PASSWORD', 'admin123'),
    ],

];
