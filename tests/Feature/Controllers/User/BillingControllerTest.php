<?php

namespace Tests\Feature\Controllers\User;

use Laravel\Cashier\Subscription;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    public function testBillingSubscribe()
    {
        $this->markTestSkipped('Requires Stripe keys');

        $response = $this->get(route('user.billing'));

        $response->assertOk();
    }

    public function testBillingGetDetails()
    {
        $this->markTestSkipped('Requires Stripe keys');

        Subscription::factory()->create([
            'user_id' => $this->user->id,
            'stripe_price' => 'foo_bar_003',
            'quantity' => 1,
        ]);

        $this->user->update([
            'stripe_id' => 'foo_bar_01',
            'card_brand' => 'visa',
            'card_last_four' => '4242',
        ]);

        $response = $this->get(route('user.billing'));

        $response->assertOk();
    }
}
