<?php

namespace Tests\Feature\Controllers\User;

use Tests\TestCase;

class LogoutSessionsControllerTest extends TestCase
{
    public function testSessionLogout()
    {
        $response = $this->post(route('user.logout'), [
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('user.settings'));

        $response->assertSessionHas('message', 'Logged out of other devices.');
    }
}
