<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;
use Illuminate\Support\Str;

class RegistrationControllerTest extends GuestTestCase
{
    public function testGetRegistration()
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function testPostRegistration()
    {
        $password = Str::random(16);

        $response = $this->post(route('register'), [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'password' => $password,
            'confirm_password' => $password,
        ]);

        $response->assertStatus(302);
    }
}
