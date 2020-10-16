<?php

namespace Database\Factories;

use App\Models\Invite;
use Illuminate\Database\Eloquent\Factories\Factory;

class InviteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invite::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'email' => $this->faker->email,
            'message' => 'Who is who',
            'token' => $this->faker->uuid,
        ];
    }
}
