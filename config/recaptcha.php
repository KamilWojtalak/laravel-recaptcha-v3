<?php

return [
    'api_site_key' => env('RECAPTCHA_SITE_KEY', ''),

    'api_secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    'url' => 'https://www.google.com/recaptcha/api/siteverify',

    /**
     * Threshold when user starts to be suspicious
     */
    'threshold' => (float) 0.5,
];
