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

    // SSO ODPC IDP
    'oidc' => [
        'base_url'      => env('OIDC_BASE_URL', 'https://api.idp.akaratmakebugs.store'),
        'client_id'     => env('OIDC_CLIENT_ID'),
        'client_secret' => env('OIDC_CLIENT_SECRET'),
        'redirect'      => env('OIDC_REDIRECT_URI', env('APP_URL', 'http://127.0.0.1:8000') . '/auth/oidc/callback'),
    ],

    // Telegram
    'telegram' => [
        'bot_token'     => env('TELEGRAM_BOT_TOKEN'),
        'chat_id'       => env('TELEGRAM_CHAT_ID'),
        'git_bot_token' => env('GIT_TELEGRAM_BOT_TOKEN'),
        'git_chat_id'   => env('GIT_TELEGRAM_CHAT_ID'),
    ],

];
