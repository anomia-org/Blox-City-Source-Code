<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    |
    | This is your site key. By default, we use a test key that is for testing
    | purposes only and does not trigger a challenge.
    |
    */

    'sitekey' => env('HCAPTCHA_SITEKEY', '89e2a2cc-361a-4f32-b948-38f6e7969384'),

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    | This is your site key. By default, we use a test key that is for testing
    | purposes only and does not trigger a challenge.
    |
    */

    'secret' => env('HCAPTCHA_SECRET', 'ES_e3afae63bd2e45f090fb07bd53dba86f'),

];
