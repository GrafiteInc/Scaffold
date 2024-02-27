<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function testTwoFactorEmail(): void
    {
        Notification::fake();

        $response = $this->put(route('user.security.two-factor'), [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'two_factor_platform' => 'email',
        ]);

        // Special note here, because the email is sent via the middleware
        // we do not get the email as something we can confirm like the test below.
        // $response->assertRedirect(route(''));

        $user = $this->user->fresh();

        // These stay null since the user is logged in
        $this->assertNull($user->two_factor_code);
        $this->assertNull($user->two_factor_expires_at);
        $this->assertNull($user->two_factor_confirmed_at);
        $this->assertNull($user->two_factor_recovery_codes);
    }

    public function testAuthenticatorTwoFactor(): void
    {
        Notification::fake();

        $response = $this->put(route('user.two-factor.update'), [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'two_factor_platform' => 'authenticator',
        ]);

        $response->assertRedirect(route('user.security.two-factor'));

        $this->assertNotNull($this->user->two_factor_code);
        $this->assertNull($this->user->two_factor_expires_at);
        $this->assertNull($this->user->two_factor_confirmed_at);
        // We have not set this cause the code is not confirmed
        $this->assertNull($this->user->two_factor_recovery_codes);

        // We do not send emails until the code is confirmed
        Notification::assertCount(0);
    }
}
