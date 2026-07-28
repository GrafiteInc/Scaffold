<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function definition()
    {
        return [
            'queue' => $this->faker->randomElement(['default', 'emails']),
            'payload' => json_encode([
                'displayName' => $this->faker->randomElement(['ExampleJob', 'AnotherJob']),
            ]),
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => now()->timestamp,
            'created_at' => now()->timestamp,
        ];
    }
}
