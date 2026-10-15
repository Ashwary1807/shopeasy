<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Portal Credentials
    |--------------------------------------------------------------------------
    |
    | Read strictly from environment variables. No usable hardcoded defaults
    | are provided in the source code to ensure secure configuration.
    |
    */
    'email' => env('ADMIN_EMAIL'),
    'password' => env('ADMIN_PASSWORD'),
];
