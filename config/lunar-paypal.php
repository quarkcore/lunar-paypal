
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Capture policy
    |--------------------------------------------------------------------------
    |
    | Here is where you can set whether you want to capture and charge payments
    | straight away, or create the Payment Intent and release them at a later date.
    |
    | automatic - Capture the payment straight away.
    | manual - Don't take payment straight away and capture later.
    |
    */
    'policy' => 'automatic',

    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_LIVE_APP_ID', 'APP-80W284485P519543T'),
    ],
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_SANDBOX_APP_ID', 'APP-80W284485P519543T'),
    ],
    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url' => env('PAYPAL_NOTIFY_URL', 'http://localhost:8000/paypal/notify'),
    'locale' => env('PAYPAL_LOCALE', 'en_US'),
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true),
];
