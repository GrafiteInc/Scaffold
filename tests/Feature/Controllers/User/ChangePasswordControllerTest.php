<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    public function test_security()
    {
        $response = $this->get(route('user.security'));

        $response->assertOk();
    }

    public function test_update_password()
    {
        $this->user->update([
            'password' => Hash::make('^testPassword2'),
        ]);

        $response = $this->put(route('user.security.update'), [
            'old_password' => '^testPassword2',
            'new_password' => '^testPassword1',
            'new_password_confirmation' => '^testPassword1',
        ]);

        $response->assertRedirect(route('user.security'));
    }
}
