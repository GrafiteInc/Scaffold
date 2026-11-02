<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => null,
            'origin_channel' => 'web',
            'status' => Thread::STATUS_OPEN,
            'last_message_at' => now(),
        ];
    }

    /**
     * Indicate that the thread is closed.
     */
    public function closed(string $reason = Thread::CLOSED_IDLE)
    {
        return $this->state(fn (array $attributes) => [
            'status' => Thread::STATUS_CLOSED,
            'closed_reason' => $reason,
            'closed_at' => now(),
        ]);
    }
}
