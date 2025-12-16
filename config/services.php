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

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'whatsapp' => [
        'appkey' => env('WHATSAPP_APPKEY', '7d389aad-ba64-4330-bde9-79aac1c52b48'),
        'authkey' => env('WHATSAPP_AUTHKEY', 'lX0GKhWw3rCJBcErpWRpQZTfz5IszhomAMm5o8dxRZ6qMfcMh6'),
        'api_url' => env('WHATSAPP_API_URL', 'https://app.saungwa.com/api/create-message'),
        'sandbox' => env('WHATSAPP_SANDBOX', 'false'),
        'admin_phone' => env('WHATSAPP_ADMIN_PHONE', '6282172292230'),
        'admin_phones' => array_filter(explode(',', env('WHATSAPP_ADMIN_PHONES', ''))), // Multiple admin phones separated by comma
    ],

    'fonnte' => [
        'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com/send'),
        'token' => env('FONNTE_TOKEN', ''),
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@dsarana.com'),
        'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_EMAILS', '')))), // Multiple admin emails separated by comma
    ],

];
