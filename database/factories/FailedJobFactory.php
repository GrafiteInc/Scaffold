<?php

namespace Database\Factories;

use App\Models\FailedJob;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FailedJobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FailedJob::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'connection' => 'database',
            'queue' => $this->faker->randomElement(['default', 'emails']),
            'payload' => json_encode([
                'displayName' => $this->faker->randomElement(['ExampleJob', 'AnotherJob']),
            ]),
            'exception' => $this->faker->sentence(),
            'failed_at' => now(),
        ];
    }
}
