<?php

return [
    /**
     * The environments you want to run Mission Control in.
     */
    'environments' => [
        'production',
    ],

    /**
     * The log levels you wish to send to Mission Control.
     */
    'levels' => [
        'emergency',
        'alert',
        'critical',
        'error',
    ],

    /**
     * The API token can be found
     * on your user API Tokens page. A token
     * is manditory for all calls to Mission Control.
     */
    'api_token' => env('MISSION_CONTROL_USER_TOKEN'),

    /**
     * The API key for your project can be found
     * on the setting page for your project. This key
     * is manditory for all calls to Mission Control.
     */
    'api_key' => env('MISSION_CONTROL_PROJECT_KEY'),
];
