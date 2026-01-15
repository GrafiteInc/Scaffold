<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
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
}
