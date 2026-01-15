<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class ResetPasswordControllerTest extends GuestTestCase
{
    public function test_get_reset_password_page()
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }
}
