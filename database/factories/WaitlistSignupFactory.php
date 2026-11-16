<?php

namespace Database\Factories;

use App\Models\WaitlistSignup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaitlistSignup>
 */
class WaitlistSignupFactory extends Factory
{
    protected $model = WaitlistSignup::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'meta' => [
                'landing_path' => '/',
            ],
        ];
    }

    public function invited(): static
    {
        return $this->state(fn () => ['invited_at' => now()]);
    }

    public function registered(): static
    {
        return $this->state(fn () => [
            'invited_at' => now()->subDay(),
            'registered_at' => now(),
        ]);
    }
}
