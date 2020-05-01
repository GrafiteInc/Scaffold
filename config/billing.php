<?php

/*
|--------------------------------------------------------------------------
| Subscription Config
|--------------------------------------------------------------------------
*/

return [
    'subscription_name' => 'main',

    'invoice' => [
        'company' => 'Grafite Inc',
        'street' => '',
        'location' => 'Ontario, Canada',
        'phone' => '',
        'url' => '',
        'product' => 'Subscription',
    ],

    'plans' => [
        env('PLAN_MONTHLY', 'plan_monthly') => [
            'price' => 9.99,
            'name' => 'Monthly',
            'frequency' => 'monthly',
        ],

        env('PLAN_YEARLY', 'plan_yearly') => [
            'price' => 89.99,
            'name' => 'Yearly',
            'frequency' => 'yearly',
        ],
    ],
];
