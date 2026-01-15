<?php

namespace Tests\Feature\Controllers\User;

use Tests\TestCase;

class LogoutSessionsControllerTest extends TestCase
{
    public function test_session_logout()
    {
        $response = $this->post(route('user.logout'), [
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('user.security'));

        $response->assertSessionHas('message', 'Logged out of other devices.');
    }
}
