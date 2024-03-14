<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
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

    'transaction' => [
        'base_uri' => env('VERIFY_TRANSACTION_URL'),
        'endpoints' => [
            'verify' => '/v3/5794d450-d2e2-4412-8131-73d0293ac1cc'
        ]
    ],

    'mail' => [
        'base_uri' => env('VERIFY_TRANSACTION_URL'),
        'endpoints' => [
            'send' => '/v3/5794d450-d2e2-4412-8131-73d0293ac1cc'
        ]
    ]

];
