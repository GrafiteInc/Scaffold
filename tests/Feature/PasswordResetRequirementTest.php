<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PasswordResetRequirementTest extends TestCase
{
    protected function flaggedUser(): User
    {
        return User::factory()->needsPasswordReset()->create([
            'password' => Hash::make('^oldPassword1'),
        ]);
    }

    public function test_flag_is_not_mass_assignable()
    {
        $user = User::factory()->create();

        $user->update(['needs_password_reset' => true]);

        $this->assertFalse($user->fresh()->needsPasswordReset());
    }

    public function test_flagged_user_is_redirected_to_security()
    {
        $this->be($this->flaggedUser());

        $this->get(route('dashboard'))->assertRedirect(route('user.security'));
        $this->get(route('teams'))->assertRedirect(route('user.security'));

        $this->get(route('user.security'))->assertOk()->assertSee('You need to change your password');
    }

    public function test_flagged_user_gets_json_403_on_ajax()
    {
        $this->be($this->flaggedUser());

        $this->postJson(route('ajax.files-upload'))
            ->assertForbidden()
            ->assertJson(['message' => 'You need to change your password before continuing.']);
    }

    public function test_unflagged_user_is_unaffected()
    {
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_impersonating_admin_is_not_redirected()
    {
        $this->be($this->flaggedUser());

        $this->withSession(['original_user' => $this->user->id])
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_changing_password_clears_flag_and_returns_to_intended_url()
    {
        $user = $this->flaggedUser();
        $this->be($user);

        $this->get(route('teams'))->assertRedirect(route('user.security'));

        $this->put(route('user.security.update'), [
            'old_password' => '^oldPassword1',
            'new_password' => '^newPassword1',
            'new_password_confirmation' => '^newPassword1',
        ])->assertRedirect(route('teams'));

        $this->assertFalse($user->fresh()->needsPasswordReset());
        $this->get(route('dashboard'))->assertOk();
    }

    public function test_new_password_must_differ_from_old()
    {
        $user = $this->flaggedUser();
        $this->be($user);

        $this->put(route('user.security.update'), [
            'old_password' => '^oldPassword1',
            'new_password' => '^oldPassword1',
            'new_password_confirmation' => '^oldPassword1',
        ])->assertSessionHasErrors('new_password');

        $this->assertTrue($user->fresh()->needsPasswordReset());
    }

    public function test_forgot_password_reset_clears_flag()
    {
        $user = $this->flaggedUser();
        $token = Password::broker()->createToken($user);

        auth()->logout();

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => '^newPassword1',
            'password_confirmation' => '^newPassword1',
        ]);

        $this->assertTrue(Hash::check('^newPassword1', $user->fresh()->password));
        $this->assertFalse($user->fresh()->needsPasswordReset());
    }

    public function test_flagged_user_can_still_use_the_api()
    {
        Sanctum::actingAs($this->flaggedUser());

        $this->getJson(route('api.users.me'))->assertOk();
    }

    public function test_admin_can_require_and_clear_password_reset()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::factory()->create());

        $this->post(route('admin.users.require-password-reset', $user))
            ->assertSessionHas('message');
        $this->assertTrue($user->fresh()->needsPasswordReset());

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSee('Password change required');

        $this->post(route('admin.users.clear-password-reset', $user))
            ->assertSessionHas('message');
        $this->assertFalse($user->fresh()->needsPasswordReset());
    }

    public function test_admin_cannot_flag_themselves()
    {
        $this->post(route('admin.users.require-password-reset', $this->user))
            ->assertStatus(422);

        $this->assertFalse($this->user->fresh()->needsPasswordReset());
    }

    public function test_service_writes_activity()
    {
        $user = User::factory()->create();

        app(UserService::class)->requirePasswordReset($user, $this->user);

        $this->assertDatabaseHas('activities', [
            'description' => "Required password reset for {$user->email}.",
        ]);
    }
}
