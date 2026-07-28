<?php

return [
    /**
     * The environments you want to run Mission Control in.
     */
    'environments' => [
        'production',
        'local',
    ],

    /**
     * The queues you want to monitor.
     */
    'queues' => [
        'default' => 'database',
        // 'sync' => 'sync',
    ],

    /**
     * The duration of seconds at which a DB query takes too long.
     */
    'query_threshold' => 5,

    /**
     * The duration of seconds at which a page load takes too long.
     */
    'page_load_threshold' => 3.5,

    /**
     * Log JavaScript errors to Mission Control.
     */
    'log_javascript_errors' => true,

    /**
     * Log web traffic to Mission Control.
     */
    'log_traffic' => true,

    /**
     * The log levels you wish to send to Mission Control.
     */
    'levels' => [
        'emergency',
        'alert',
        'critical',
        'error',
        // 'info',
        // 'notice',
        // 'warning',
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

    /**
     * The UUID for your project can be found
     * on the setting page for your project. This key
     * is manditory for webhook calls to Mission Control.
     *
     * It is required for JavaScript error reporting!
     */
    'api_uuid' => env('MISSION_CONTROL_PROJECT_UUID'),

    /**
     * The URL for Mission Control. Custom domains are an option.
     */
    'url' => env('MISSION_CONTROL_URL', 'https://missioncontrolapp.io'),

    /**
     * Control your attack monitoring
     */
    'attacks' => [
        'xss' => [
            // Evil starting attributes
            // '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|xmlns|xlink:href)[^>]*>?#iUu',
            // javascript:, livescript:, vbscript:, mocha: protocols
            '!((java|live|vb)script|mocha|feed):(\w)*!iUu',
            '#-moz-binding[\x00-\x20]*:#u',
            // Unneeded tags
            // '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>?#i',
        ],
        'php' => [
            'bzip2://',
            'expect://',
            'glob://',
            'phar://',
            'php://',
            'ogg://',
            'rar://',
            'ssh2://',
            'zip://',
            'zlib://',
        ],
        // 'lfi' => [
        //     '#\.\/#is',
        // ],
        'rfi' => [
            '#(http|ftp){1,1}(s){0,1}://.*#i',
        ],
    ],
];
