<?php

namespace Tests\Feature\Controllers\User;

use App\Http\Controllers\User\PushSubscriptionController;
use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PushSubscriptionControllerTest extends TestCase
{
    protected $endpoint = 'https://fcm.googleapis.com/fcm/send/abc123';

    protected function payload(array $overrides = [])
    {
        return array_merge([
            'endpoint' => $this->endpoint,
            'keys' => [
                'p256dh' => 'BNcRdreALRFXTkOOUHK1EtK2wtaz5Ry4YfYCA_0QTpQtUbVlUls0VJXg7A8u-Ts1XbjhazAkj7I99e8QcYP7DkM',
                'auth' => 'tBHItJI5svbpez7KI4CCXg',
            ],
            'contentEncoding' => 'aes128gcm',
        ], $overrides);
    }

    public function test_store_registers_the_subscription()
    {
        $response = $this->postJson(route('user.push-subscriptions.store'), $this->payload());

        $response->assertOk();
        $this->assertEquals(1, $this->user->pushSubscriptions()->count());
        $this->assertEquals($this->endpoint, $this->user->pushSubscriptions()->first()->endpoint);
        $response->assertSessionHas(PushSubscriptionController::SESSION_KEY, $this->endpoint);
    }

    public function test_store_is_idempotent_for_the_same_endpoint()
    {
        $this->postJson(route('user.push-subscriptions.store'), $this->payload());
        $this->postJson(route('user.push-subscriptions.store'), $this->payload())->assertOk();

        $this->assertEquals(1, $this->user->pushSubscriptions()->count());
    }

    public function test_store_moves_an_endpoint_to_the_new_user()
    {
        $this->postJson(route('user.push-subscriptions.store'), $this->payload());

        $other = User::factory()->create();
        $this->actingAs($other);

        $this->postJson(route('user.push-subscriptions.store'), $this->payload())->assertOk();

        $this->assertEquals(0, $this->user->pushSubscriptions()->count());
        $this->assertEquals(1, $other->pushSubscriptions()->count());
    }

    public function test_store_validates_the_payload()
    {
        $response = $this->postJson(route('user.push-subscriptions.store'), [
            'endpoint' => 'http://not-secure.example.com',
            'keys' => [],
            'contentEncoding' => 'gzip',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['endpoint', 'keys.p256dh', 'keys.auth', 'contentEncoding']);
    }

    public function test_destroy_removes_the_subscription()
    {
        $this->user->updatePushSubscription($this->endpoint, 'key', 'token');

        $response = $this->deleteJson(route('user.push-subscriptions.destroy'), [
            'endpoint' => $this->endpoint,
        ]);

        $response->assertOk();
        $this->assertEquals(0, $this->user->pushSubscriptions()->count());
    }

    public function test_test_sends_an_in_app_notification()
    {
        Notification::fake();

        $this->user->updatePushSubscription($this->endpoint, 'key', 'token');

        $this->postJson(route('user.push-subscriptions.test'))->assertOk();

        Notification::assertSentTo($this->user, InAppNotification::class);
    }

    public function test_test_requires_a_registered_device()
    {
        Notification::fake();

        $this->postJson(route('user.push-subscriptions.test'))->assertStatus(422);

        Notification::assertNothingSent();
    }

    public function test_test_respects_the_user_setting()
    {
        Notification::fake();

        $this->user->updatePushSubscription($this->endpoint, 'key', 'token');
        $this->user->update(['allow_push_notifications' => false]);

        $this->postJson(route('user.push-subscriptions.test'))->assertStatus(422);

        Notification::assertNothingSent();
    }

    public function test_logout_removes_this_browsers_subscription()
    {
        $this->postJson(route('user.push-subscriptions.store'), $this->payload());
        $this->user->updatePushSubscription('https://updates.push.services.mozilla.com/other', 'key', 'token');

        Auth::guard('web')->logout();

        $this->assertEquals(
            ['https://updates.push.services.mozilla.com/other'],
            $this->user->pushSubscriptions()->pluck('endpoint')->all()
        );
    }
}
