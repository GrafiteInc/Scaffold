<?php

namespace Tests\Feature\Controllers;

use App\Models\WaitlistSignup;
use App\Notifications\WaitlistConfirmation;
use Illuminate\Support\Facades\Notification;
use Tests\GuestTestCase;

class WaitlistControllerTest extends GuestTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function test_waitlist_page_renders()
    {
        $response = $this->get(route('waitlist'));

        $response->assertOk();
        $response->assertSee('Join the waitlist');
    }

    public function test_home_is_the_landing_page_only_when_enabled()
    {
        config(['general.waitlist_enabled' => false]);
        $this->get(route('home'))->assertOk()->assertDontSee('Join the waitlist');

        config(['general.waitlist_enabled' => true]);
        $this->get(route('home'))->assertOk()->assertSee('Join the waitlist');
    }

    public function test_tracking_params_are_carried_into_the_form()
    {
        $response = $this->get(route('waitlist', ['utm_source' => 'newsletter']));

        $response->assertSee('name="utm_source"', false);
        $response->assertSee('value="newsletter"', false);
    }

    public function test_join_stores_signup_and_sends_confirmation()
    {
        $response = $this->from(route('waitlist'))->post(route('waitlist.store'), [
            'email' => ' Don@SterlingCooper.com ',
            'name' => 'Don',
            'landing_path' => '/waitlist',
            'utm_source' => 'newsletter',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('waitlist'));
        $response->assertSessionHas('waitlist_joined', true);

        $signup = WaitlistSignup::firstWhere('email', 'don@sterlingcooper.com');

        $this->assertNotNull($signup);
        $this->assertSame('Don', $signup->name);
        $this->assertSame('newsletter', $signup->meta['utm_source']);
        $this->assertSame('/waitlist', $signup->meta['landing_path']);
        $this->assertSame('pending', $signup->status);

        Notification::assertSentOnDemand(
            WaitlistConfirmation::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'don@sterlingcooper.com'
        );
    }

    public function test_joining_twice_is_a_silent_no_op()
    {
        WaitlistSignup::factory()->create(['email' => 'don@sterlingcooper.com', 'name' => 'Don']);

        $response = $this->post(route('waitlist.store'), [
            'email' => 'don@sterlingcooper.com',
            'name' => 'Someone Else',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('waitlist_joined', true);
        $this->assertSame(1, WaitlistSignup::count());
        $this->assertSame('Don', WaitlistSignup::first()->name);
        Notification::assertNothingSent();
    }

    public function test_join_requires_a_valid_email()
    {
        $this->post(route('waitlist.store'), ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, WaitlistSignup::count());
    }

    public function test_join_rejects_disposable_email()
    {
        $this->post(route('waitlist.store'), ['email' => 'peggy@10minutemail.com'])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, WaitlistSignup::count());
    }

    public function test_join_rejects_blocked_domains()
    {
        config(['general.registration_blocked_domains' => ['mccann.com']]);

        $this->post(route('waitlist.store'), ['email' => 'pete@mccann.com'])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, WaitlistSignup::count());
    }
}
