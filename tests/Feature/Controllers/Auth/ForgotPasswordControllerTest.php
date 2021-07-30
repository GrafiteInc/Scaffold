<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class ForgotPasswordControllerTest extends GuestTestCase
{
    public function testGetForgotPassword()
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }
}
