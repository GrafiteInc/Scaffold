<?php

use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Notifications\DatabaseNotification;

/*
 * --------------------------------------------------------------------------
 * Activity Factory
 * --------------------------------------------------------------------------
*/

$factory->define(DatabaseNotification::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'type' => InAppNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => 1,
        'data' => json_encode([
            'is_important' => true,
            'message' => 'You reset your API token.',
        ]),
    ];
});
