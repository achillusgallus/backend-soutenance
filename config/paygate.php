<?php

return [
    'api_key' => env('PAYGATE_API_KEY', ''),
    'api_key_debug' => env('PAYGATE_API_KEY_DEBUG', ''),
    'webhook_secret' => env('PAYGATE_WEBHOOK_SECRET', ''),
    'verify_url' => env('PAYGATE_VERIFY_URL', 'https://api.paygate.global/v2/verify'),
];
