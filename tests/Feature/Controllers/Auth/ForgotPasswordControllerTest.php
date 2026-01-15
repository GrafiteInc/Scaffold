<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class ForgotPasswordControllerTest extends GuestTestCase
{
    public function test_get_forgot_password()
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }
}
