<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class SecurityControllerTest extends TestCase
{
    public function testSecurity()
    {
        $response = $this->get(route('user.security'));

        $response->assertOk();
    }

    public function testUpdatePassword()
    {
        $this->user->update([
            'password' => Hash::make('admin'),
        ]);

        $response = $this->put(route('user.security.update'), [
            'old_password' => 'admin',
            'new_password' => 'testPassword1',
            'new_password_confirmation' => 'testPassword1',
        ]);

        $response->assertRedirect(route('user.settings'));
    }
}
