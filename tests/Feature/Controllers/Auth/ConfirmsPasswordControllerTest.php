<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;

class ConfirmsPasswordControllerTest extends TestCase
{
    public function testGetConfirmPasswordPage()
    {
        $response = $this->get(route('password.confirm'));

        $response->assertOk();
    }
}
