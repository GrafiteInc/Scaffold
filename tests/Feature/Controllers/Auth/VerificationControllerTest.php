<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;

class VerificationControllerTest extends TestCase
{
    public function test_get_verification_page()
    {
        // Email is pre-verified for the test user
        $response = $this->get(route('verification.notice'));

        $response->assertRedirect(route('dashboard'));
    }
}
