<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    public function testSecurity()
    {
        $response = $this->get(route('user.settings.password'));

        $response->assertOk();
    }

    public function testUpdatePassword()
    {
        $this->user->update([
            'password' => Hash::make('^testPassword2'),
        ]);

        $response = $this->put(route('user.settings.password.update'), [
            'old_password' => '^testPassword2',
            'new_password' => '^testPassword1',
            'new_password_confirmation' => '^testPassword1',
        ]);

        $response->assertRedirect(route('user.settings'));
    }
}
