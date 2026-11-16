<?php

namespace Tests\Feature\Controllers\Admin;

use App\Events\UserDisabled;
use App\Events\UserEnabled;
use App\Models\Activity;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_index()
    {
        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $response = $this->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Invite New User');
        $response->assertSee('joe@grafite.ca');
    }

    public function test_search()
    {
        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $role = Role::factory()->create([
            'name' => 'admin',
            'label' => 'Admin',
        ]);

        $user->roles()->attach($role);

        $response = $this->get(route('admin.users.search', [
            'search' => 'moe',
        ]));

        $response->assertOk();
        $response->assertDontSee('joe@grafite.ca');
    }

    public function test_get_invite()
    {
        $response = $this->get(route('admin.users.invite'));

        $response->assertOk();
        $response->assertSee('Roles');
        $response->assertSee('Email');
        $response->assertSee('Send');
    }

    public function test_post_invite()
    {
        $response = $this->post(route('admin.users.invite'), [
            'email' => 'jim@grafite.ca',
            'roles' => 'admin',
        ]);

        $response->assertStatus(302);

        $count = Invite::where('relationship', null)
            ->where('model_id', null)
            ->where('email', 'jim@grafite.ca')
            ->count();
        $this->assertEquals(1, $count);
    }

    public function test_edit()
    {
        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $response = $this->get(route('admin.users.edit', [$user->id]));

        $response->assertStatus(200);
        $response->assertSee('No known activities');
        $response->assertSee('Name');
        $response->assertSee('Joe');
    }

    public function test_edit_shows_activity_offcanvas()
    {
        $user = User::factory()->create(['name' => 'Joe']);
        $user->roles()->attach(Role::factory()->create());

        Activity::factory()->create([
            'user_id' => $user->id,
            'description' => 'Settings updated.',
            'request' => [
                'url' => 'https://example.test/user/settings',
                'method' => 'PATCH',
                'query' => 'https://example.test/user/settings?tab=profile',
                'secure' => true,
                'client_ip' => '10.0.0.1',
                'payload' => [
                    '_token' => 'csrf-token-value',
                    'name' => 'Joe Smith',
                    'password' => 'hunter2-secret',
                    'bio' => '<script>alert(1)</script>',
                    'meta' => ['timezone' => 'UTC'],
                ],
            ],
        ]);

        $response = $this->get(route('admin.users.edit', [$user->id]));

        $response->assertOk();
        $response->assertSee('id="userActivity"', false);
        $response->assertSee('Settings updated.');
        $response->assertSee('PATCH');
        $response->assertSee('/user/settings?tab=profile');
        $response->assertSee('10.0.0.1');
        $response->assertSee('Joe Smith');
        $response->assertSee('timezone');
        $response->assertDontSee('hunter2-secret');
        $response->assertDontSee('csrf-token-value');
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_update()
    {
        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $response = $this->put(route('admin.users.update', [$user->id]), [
            'name' => 'Moe',
            'email' => 'joe@grafite.ca',
        ]);

        $response->assertStatus(302);
        $count = User::where('name', 'Moe')->count();
        $this->assertEquals(1, $count);
    }

    public function test_login_as_user()
    {
        $role = Role::factory()->create([
            'name' => 'member',
            'label' => 'Member',
        ]);

        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->post(route('admin.users.switch', [$user->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('original_user', $this->user->id);
    }

    public function test_return_to_login_from_user()
    {
        $role = Role::factory()->create([
            'name' => 'member',
            'label' => 'Member',
        ]);

        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $user->roles()->attach($role->id);

        $response = $this->post(route('admin.users.switch', [$user->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('original_user', $this->user->id);

        $response = $this->post(route('users.return-switch'));
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasNoErrors();
    }

    public function test_delete()
    {
        $user = User::factory()->create([
            'name' => 'Joe',
            'email' => 'joe@grafite.ca',
        ]);

        $response = $this->delete(route('admin.users.destroy', [$user->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
    }

    public function test_disable_user()
    {
        Event::fake([UserDisabled::class]);

        $user = User::factory()->create();
        $user->createToken('device');

        $this->post(route('admin.users.disable', $user), [
            'reason' => 'Abuse',
            'until' => now()->addDay()->format('Y-m-d\\TH:i'),
        ])->assertSessionHas('message', 'User disabled');

        $user->refresh();
        $this->assertTrue($user->isDisabled());
        $this->assertSame('Abuse', $user->disabled_reason);
        $this->assertSame($this->user->id, $user->disabled_by);
        $this->assertNotNull($user->disabled_until);
        $this->assertCount(0, $user->tokens()->get());
        Event::assertDispatched(UserDisabled::class);
    }

    public function test_disable_user_permanently()
    {
        $user = User::factory()->create();

        $this->post(route('admin.users.disable', $user))->assertSessionHas('message', 'User disabled');

        $user->refresh();
        $this->assertTrue($user->isDisabled());
        $this->assertNull($user->disabled_until);
    }

    public function test_disable_rejects_past_until()
    {
        $user = User::factory()->create();

        $this->post(route('admin.users.disable', $user), ['until' => now()->subDay()->toDateTimeString()])
            ->assertSessionHasErrors('until');

        $this->assertFalse($user->fresh()->isDisabled());
    }

    public function test_cannot_disable_yourself()
    {
        $this->post(route('admin.users.disable', $this->user))->assertStatus(422);

        $this->assertFalse($this->user->fresh()->isDisabled());
    }

    public function test_enable_user()
    {
        Event::fake([UserEnabled::class]);

        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now()])->save();

        $this->post(route('admin.users.enable', $user))->assertSessionHas('message', 'User enabled');

        $this->assertFalse($user->fresh()->isDisabled());
        Event::assertDispatched(UserEnabled::class);
    }

    public function test_edit_shows_disabled_state()
    {
        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now(), 'disabled_reason' => 'Chargebacks', 'disabled_by' => $this->user->id])->save();
        $user->roles()->attach(Role::factory()->create());

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSee('permanently')
            ->assertSee('Chargebacks')
            ->assertSee('Enable User')
            ->assertDontSee('Login as this User');
    }

    public function test_edit_shows_disable_form_for_active_user()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::factory()->create());

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSee('Disable User')
            ->assertSee(route('admin.users.disable', $user));
    }

    public function test_cannot_switch_to_disabled_user()
    {
        $user = User::factory()->create();
        $user->forceFill(['disabled_at' => now()])->save();

        $this->post(route('admin.users.switch', $user))->assertSessionHasErrors();

        $this->assertAuthenticatedAs($this->user);
    }
}
