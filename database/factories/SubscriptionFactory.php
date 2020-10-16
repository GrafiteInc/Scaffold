<?php

namespace Database\Factories;

use Laravel\Cashier\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => 'main',
            'stripe_id' => 'sub_324b23kj4b',
            'stripe_plan' => 'plan_monthly',
            'stripe_status' => 'active',
            'quantity' => 1,
        ];
    }
}
