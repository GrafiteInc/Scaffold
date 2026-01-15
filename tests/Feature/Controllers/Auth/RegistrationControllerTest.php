<?php

namespace Tests\Feature\Controllers\Auth;

use Illuminate\Support\Str;
use Tests\GuestTestCase;

class RegistrationControllerTest extends GuestTestCase
{
    public function test_get_registration()
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_post_registration()
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
