<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '110979386282749',
        'client_secret' => 'a2bee04c75eb7b898df8e88acd3db350',
        'redirect' => env('FACEBOOK_CALLBACK'),
    ],

    'google' => [
        'client_id' => '7270026181-ua2ts72n80naftjc5sefc4al3j2cshve.apps.googleusercontent.com',
        'client_secret' => 'bt6SOi_T8z12tApNMPdOnqNc',
        'redirect' => env('GOOGLE_CALLBACK'),
    ],

];
