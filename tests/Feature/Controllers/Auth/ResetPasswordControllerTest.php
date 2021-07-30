<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class ResetPasswordControllerTest extends GuestTestCase
{
    public function testGetResetPasswordPage()
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }
}
