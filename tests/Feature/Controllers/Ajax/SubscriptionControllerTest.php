<?php

namespace Tests\Feature\Controllers\Ajax;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    public function testCreateSubsciption()
    {
        $this->markTestSkipped('Requires Stripe keys');

        Notification::fake();

        $response = $this->post(route('ajax.billing.subscription.create'), [
            'state' => 'Ontario',
            'billing_email' => 'who@areyou.com',
            'country' => 'Canada',
            'plan' => 'plan_FgRNFn5SPrDG6g',
            'payment_method' => 'pm_card_visa',
        ]);

        $response->assertSessionHas('message', 'Subscribed to Monthly plan.');
        $response->assertJson(['message' => 'You\'re now subscribed!']);
    }

    public function testUpdatePaymentMethod()
    {
        $this->markTestSkipped('Requires Stripe keys');

        Notification::fake();

        $this->user->update([
            'card_last_four' => 4444,
        ]);

        $response = $this->post(route('ajax.billing.subscription.payment-method'), [
            'payment_method' => 'pm_card_mastercard',
        ]);

        $response->assertJson(['message' => 'Card change failed']);
    }
}
