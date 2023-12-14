<?php

/*
|--------------------------------------------------------------------------
| Subscription Config
|--------------------------------------------------------------------------
*/

return [
    'subscription_name' => 'default',

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
            'key' => env('PLAN_MONTHLY', 'plan_monthly'),
            'label' => 'Monthly ($9.99)',
            'currency' => 'USD',
            'encouraged' => false,
            'pricing' => '$9.99/month',
            'features' => [],
        ],

        env('PLAN_YEARLY', 'plan_yearly') => [
            'price' => 89.99,
            'name' => 'Yearly',
            'frequency' => 'yearly',
            'key' => env('PLAN_YEARLY', 'plan_yearly'),
            'label' => 'Yearly ($89.99) saving 25%!',
            'currency' => 'USD',
            'encouraged' => true,
            'pricing' => '$99.99/year',
            'features' => [],
        ],
    ],
];
