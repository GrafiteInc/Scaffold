<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;

class RegistrationControllerTest extends GuestTestCase
{
    public function testGetRegistration()
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }
}
