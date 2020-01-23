<?php

use Laravel\Cashier\Subscription;

/*
|--------------------------------------------------------------------------
| User Factory
|--------------------------------------------------------------------------
*/

$factory->define(Subscription::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'user_id' => 1,
        'name' => 'main',
        'stripe_id' => 'sub_324b23kj4b',
        'stripe_plan' => 'plan_monthly',
        'stripe_status' => 'active',
        'quantity' => 1,
    ];
});
