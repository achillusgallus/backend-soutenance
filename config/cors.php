<?php

// return [
//     /*
//     |--------------------------------------------------------------------------
//     | Cross-Origin Resource Sharing (CORS) Configuration
//     |--------------------------------------------------------------------------
//     |
//     | This file determines the CORS settings for the application.
//     | Adjust the values as needed for your environment.
//     |
//     */

//     'paths' => [
//         'api/*',
//         'sanctum/csrf-cookie',
//     ],

//     'allowed_methods' => ['*'],

//     'allowed_origins' => [
//         'http://localhost:52183',
//         'http://127.0.0.1:8000',
//     ],

//     'allowed_origins_patterns' => [],

//     'allowed_headers' => ['*'],

//     'exposed_headers' => [],

//     'max_age' => 0,

//     'supports_credentials' => false,
// ];

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost',
        'http://localhost:3000',
        'http://localhost:8080',
        'http://10.0.2.2:34002',
        'http://localhost:5173',
        'http://localhost:8000',
        'http://127.0.0.1:*',
        'https://togoschool-dd065.web.app',
        'https://kannon-spadiceous-tangly.ngrok-free.dev',
        'https://backend-togoschool.onrender.com',
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];