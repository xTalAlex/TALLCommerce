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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY',''),
        'secret' => env('STRIPE_SECRET',''),
        'signing_secret' => env('STRIPE_WEBHOOK_SECRET',''),
    ],

    'paypal' => [
        'client' => env('PAYPAL_CLIENT',''),
        'secret' => env('PAYPAL_SECRET',''),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT',''),
        'client_secret' => env('GOOGLE_SECRET',''),
        'redirect' => env('GOOGLE_CALLBACK', env('APP_URL').'/auth/google/callback'),
        'analytics' => env('GOOGLE_ANALYTICS', ''),
    ],

    'tawkto' => [
        'src' => env('TAWKTO_SRC',''),
    ],

    'slack' => [
        'webhook' => env('NOTIFICATIONS_SLACK_WEBHOOK',''),
    ],

];
