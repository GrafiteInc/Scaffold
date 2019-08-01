<?php

/*
|--------------------------------------------------------------------------
| Team Factory
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Invite::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'email' => $faker->email,
        'message' => 'Who is who',
        'token' => $faker->uuid,
    ];
});
