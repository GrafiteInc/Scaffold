<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class TwoFactorControllerTest extends TestCase
{
    public function testGetTwoFactorPage()
    {
        $response = $this->get(route('verification.two-factor'));

        $response->assertOk();
    }

    public function testVerifyTwoFactorEmail()
    {
        Notification::fake();

        $this->user->update([
            'two_factor_platform' => 'email',
        ]);

        $this->user->setAndSendTwoFactorForEmail();

        // Means the email was sent!
        Notification::assertCount(1);

        // We're doing this because we dont want to pass the code around
        $this->user->update([
            'two_factor_code' => encrypt('456789'),
        ]);

        $response = $this->post(route('verification.two-factor'), [
            'one_time_passcode' => '456789',
        ]);

        $this->assertNotNull($this->user->two_factor_confirmed_at);

        $response->assertRedirect(route('dashboard'));
    }
}
