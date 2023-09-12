<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MT5 Credentials
    |--------------------------------------------------------------------------
    |
    | this is credentials for access to the mt5 server account
    |
    */

    'mt5' => [
        'ip' => env('MT5_SERVER_IP', '127.0.0.1'),
        'port' => env('MT5_SERVER_PORT', 443),
        'login' => env('MT5_SERVER_WEB_LOGIN', ''),
        'password' => env('MT5_SERVER_WEB_PASSWORD', ''),
    ],

    'log_file_location' => 'logs/mt5_logs', // set to full path for log files

    /*
    |--------------------------------------------------------------------------
    | Package Route and Middleware
    |--------------------------------------------------------------------------
    |
    | this is where you set the routes used by the package
    |
    */
    'route' => [
        'prefix' => 'mt5',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package To Be Used With REST End Point
    |--------------------------------------------------------------------------
    |
    | this is where you set enable the REST endpoints.
    |
    */
    'enable_rest' => false,

    'locale'    => 'en_US',

    'timezone' => "Asia/Bangkok",

    'version' => 2815
];