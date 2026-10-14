<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\ThreadMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThreadMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'thread_id' => Thread::factory(),
            'user_id' => fn (array $attributes) => Thread::find($attributes['thread_id'])->user_id,
            'role' => ThreadMessage::ROLE_USER,
            'channel' => ThreadMessage::CHANNEL_WEB,
            'content' => $this->faker->sentence(),
            'status' => ThreadMessage::STATUS_COMPLETED,
        ];
    }

    /**
     * A reply from the assistant.
     */
    public function fromAssistant()
    {
        return $this->state(fn (array $attributes) => [
            'role' => ThreadMessage::ROLE_ASSISTANT,
        ]);
    }
}
