<?php

namespace Database\Factories;

use App\Models\DatabaseNotification;
use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DatabaseNotification::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'type' => InAppNotification::class,
            'notifiable_type' => User::class,
            'notifiable_id' => 1,
            'data' => json_encode([
                'is_important' => true,
                'message' => 'You reset your API token.',
            ]),
        ];
    }
}
