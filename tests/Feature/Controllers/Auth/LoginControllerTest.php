<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class LoginControllerTest extends GuestTestCase
{
    public function testGetLoginPage()
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }
}
