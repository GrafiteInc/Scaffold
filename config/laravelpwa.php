<?php

use Grafite\Forms\Fields\Dropzone;

return [
    'name' => env('APP_NAME', 'Scaffold'),
    'version' => env('APP_VERSION', '1.0'),

    /*
     * Whether to register the service worker. Defaults to production only —
     * set PWA_SERVICE_WORKER=true locally to exercise offline and push
     * without deploying.
     */
    'register_service_worker' => env('PWA_SERVICE_WORKER', env('APP_ENV') === 'production'),

    /*
     * Form fields whose CDN files the service worker fetches while the browser
     * is idle, so their editors open from cache the first time. Add heavier
     * fields here as forms start using them (Quill2, Datepicker, Flatpickr).
     */
    'warm_fields' => [
        Dropzone::class,
    ],

    /*
     * Third-party hosts the service worker may cache static files from. Keep
     * this to asset CDNs: analytics, captcha, payment and API hosts must not
     * be cached.
     */
    'cdn_hosts' => [
        'cdn.jsdelivr.net',
        'cdnjs.cloudflare.com',
        'code.jquery.com',
        'unpkg.com',
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'kit.fontawesome.com',
        'ka-f.fontawesome.com',
        'ka-p.fontawesome.com',
    ],

    'manifest' => [
        'name' => env('APP_NAME', 'Scaffold'),
        'short_name' => 'Scaffold',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            '180x180' => [
                'path' => '/images/icons/apple-icon-180.png',
                'purpose' => 'any',
            ],
            '192x192' => [
                'path' => '/images/icons/icon-192x192.png',
                'purpose' => 'any',
            ],
            '512x512' => [
                'path' => '/images/icons/icon-512x512.png',
                'purpose' => 'any',
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/apple-splash-640-1136.jpg',
            '1136x640' => '/images/icons/apple-splash-1136-640.jpg',
            '750x1334' => '/images/icons/apple-splash-750-1334.jpg',
            '1334x750' => '/images/icons/apple-splash-1334-750.jpg',
            '1242x2208' => '/images/icons/apple-splash-1242-2208.jpg',
            '2208x1242' => '/images/icons/apple-splash-2208-1242.jpg',
            '828x1792' => '/images/icons/apple-splash-828-1792.jpg',
            '1792x828' => '/images/icons/apple-splash-1792-828.jpg',
            '1242x2688' => '/images/icons/apple-splash-1242-2688.jpg',
            '2688x1242' => '/images/icons/apple-splash-2688-1242.jpg',
            '1125x2436' => '/images/icons/apple-splash-1125-2436.jpg',
            '2436x1125' => '/images/icons/apple-splash-2436-1125.jpg',
            '1170x2532' => '/images/icons/apple-splash-1170-2532.jpg',
            '2532x1170' => '/images/icons/apple-splash-2532-1170.jpg',
            '1284x2778' => '/images/icons/apple-splash-1284-2778.jpg',
            '2778x1284' => '/images/icons/apple-splash-2778-1284.jpg',
            '1620x2160' => '/images/icons/apple-splash-1620-2160.jpg',
            '2160x1620' => '/images/icons/apple-splash-2160-1620.jpg',
            '1668x2224' => '/images/icons/apple-splash-1668-2224.jpg',
            '2224x1668' => '/images/icons/apple-splash-2224-1668.jpg',
            '1536x2048' => '/images/icons/apple-splash-1536-2048.jpg',
            '2048x1536' => '/images/icons/apple-splash-2048-1536.jpg',
            '1668x2388' => '/images/icons/apple-splash-1668-2388.jpg',
            '2388x1668' => '/images/icons/apple-splash-2388-1668.jpg',
            '2048x2732' => '/images/icons/apple-splash-2048-2732.jpg',
            '2732x2048' => '/images/icons/apple-splash-2732-2048.jpg',
        ],
        'custom' => [],
    ],
];
