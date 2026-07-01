<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key' => env('DUITKU_API_KEY'),
    'sandbox' => env('DUITKU_SANDBOX', true),
    'callback_url' => env('DUITKU_CALLBACK_URL'),
    'return_url' => env('DUITKU_RETURN_URL'),
    'payment_method' => env('DUITKU_PAYMENT_METHOD', 'VC'),
    'timeout' => env('DUITKU_TIMEOUT', 15),
    'sandbox_base_url' => 'https://sandbox.duitku.com/webapi/api/merchant/v2',
    'production_base_url' => 'https://passport.duitku.com/webapi/api/merchant/v2',
];
