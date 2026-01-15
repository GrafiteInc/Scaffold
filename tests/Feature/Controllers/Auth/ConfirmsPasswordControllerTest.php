<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;

class ConfirmsPasswordControllerTest extends TestCase
{
    public function test_get_confirm_password_page()
    {
        $response = $this->get(route('password.confirm'));

        $response->assertOk();
    }
}
