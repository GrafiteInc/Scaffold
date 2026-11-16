<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\GuestTestCase;

class RegistrationControllerTest extends GuestTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(['name' => 'member', 'label' => 'Member']);
    }

    protected function register(string $email, string $route = 'register', array $extra = [])
    {
        $password = Str::random(16).'aA1!';

        return $this->post(route($route), array_merge([
            'name' => fake()->name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ], $extra));
    }

    public function test_get_registration()
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_post_registration()
    {
        $response = $this->register('peggy@sterlingcooper.com');

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'peggy@sterlingcooper.com']);
    }

    public function test_post_registration_rejects_disposable_email()
    {
        $response = $this->register('peggy@10minutemail.com');

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'peggy@10minutemail.com']);
    }

    public function test_post_registration_rejects_domains_outside_allow_list()
    {
        config(['general.registration_allowed_domains' => ['sterlingcooper.com']]);

        $this->register('peggy@gmail.com')->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'peggy@gmail.com']);
    }

    public function test_post_registration_rejects_blocked_domains()
    {
        config(['general.registration_blocked_domains' => ['mccann.com']]);

        $this->register('pete@mccann.com')->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'pete@mccann.com']);
    }

    public function test_invite_registration_skips_domain_lists()
    {
        config(['general.registration_allowed_domains' => ['sterlingcooper.com']]);

        $inviter = User::factory()->create();
        $invite = Invite::factory()->create(['user_id' => $inviter->id, 'email' => 'joan@gmail.com']);

        $response = $this->register('joan@gmail.com', 'register.invite', ['activation_token' => $invite->token]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'joan@gmail.com']);
    }

    public function test_invite_registration_rejects_disposable_email()
    {
        $inviter = User::factory()->create();
        $invite = Invite::factory()->create(['user_id' => $inviter->id, 'email' => 'joan@10minutemail.com']);

        $response = $this->register('joan@10minutemail.com', 'register.invite', ['activation_token' => $invite->token]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'joan@10minutemail.com']);
    }
}
