<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Middleware\EnsureUserIsActive;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Tests\GuestTestCase;

class LoginControllerTest extends GuestTestCase
{
    public function test_get_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }

    public function test_disabled_user_cannot_log_in()
    {
        Event::fake([Login::class]);

        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now()])->save();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret'])
            ->assertSessionHasErrors(['email' => EnsureUserIsActive::MESSAGE]);

        $this->assertGuest();
        Event::assertNotDispatched(Login::class);
    }

    public function test_wrong_password_for_disabled_user_shows_generic_error()
    {
        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now()])->save();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => trans('auth.failed')]);
    }

    public function test_user_can_log_in_after_timed_disable_passes()
    {
        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now()->subDay(), 'disabled_until' => now()->subMinute()])->save();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }
}
