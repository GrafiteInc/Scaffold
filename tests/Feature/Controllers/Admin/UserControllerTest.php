<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testIndex()
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

    public function testSearch()
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

    public function testGetInvite()
    {
        $response = $this->get(route('admin.users.invite'));

        $response->assertOk();
        $response->assertSee('Roles');
        $response->assertSee('Email');
        $response->assertSee('Send');
    }

    public function testPostInvite()
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

    public function testEdit()
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

    public function testUpdate()
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

    public function testLoginAsUser()
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

    public function testReturnToLoginFromUser()
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

    public function testDelete()
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
