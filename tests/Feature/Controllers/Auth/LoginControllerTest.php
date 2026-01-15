<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class LoginControllerTest extends GuestTestCase
{
    public function test_get_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }
}
