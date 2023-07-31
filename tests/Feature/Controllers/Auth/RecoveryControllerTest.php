<?php

namespace Tests\Feature\Controllers\Auth;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecoveryControllerTest extends TestCase
{
    public function testGetRecoveryPage()
    {
        $response = $this->get(route('recovery'));

        $response->assertOk();
    }

    public function testVerifyRecovery()
    {
        Notification::fake();

        $this->user->update([
            'two_factor_platform' => 'authenticator',
        ]);

        $this->user->setTwoFactorForAuthenticator();

        // Means codes were sent
        Notification::assertCount(1);

        $codes = Str::of($this->user->two_factor_recovery_codes)->explode(',');

        $response = $this->post(route('recovery.verify'), [
            'email' => $this->user->email,
            'recovery_code' => $codes[4],
        ]);

        $user = $this->user->fresh();

        $this->assertNull($user->two_factor_confirmed_at);
        $this->assertNull($user->two_factor_platform);
        $this->assertNull($user->two_factor_expires_at);
        $this->assertNull($user->two_factor_code);

        $response->assertRedirect(route('dashboard'));
    }
}
