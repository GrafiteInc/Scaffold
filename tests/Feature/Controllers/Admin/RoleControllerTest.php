<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertSee('Admin');
        $response->assertSee('Roles');
    }

    public function test_create()
    {
        $response = $this->get(route('admin.roles.create'));

        $response->assertOk();
        $response->assertSee('Label');
        $response->assertSee('Permissions');
    }

    public function test_store()
    {
        $response = $this->post(route('admin.roles.store'), [
            'label' => 'Subscriber',
            'name' => 'subscriber',
            'permissions' => [
                'users.invite' => true,
                'users.activity' => true,
            ],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Role::where('name', 'subscriber')->count() > 0);
        $this->assertEquals([
            'users.invite',
            'users.activity',
        ], Role::where('name', 'subscriber')->first()->permissions);
    }

    public function test_edit()
    {
        $role = Role::factory()->create();

        $response = $this->get(route('admin.roles.edit', [$role->id]));

        $response->assertOk();
        $response->assertSee('Label');
        $response->assertSee('Permissions');
    }

    public function test_update()
    {
        $role = Role::factory()->create();

        $response = $this->put(route('admin.roles.update', [$role->id]), [
            'label' => 'Subscriber',
            'permissions' => [
                'users.invite' => true,
            ],
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Role::where('name', 'subscriber')->count() > 0);
        $this->assertEquals(['users.invite'], Role::where('name', 'subscriber')->first()->permissions);
    }

    public function test_delete()
    {
        $role = Role::factory()->create([
            'label' => 'Subscriber',
        ]);

        $response = $this->delete(route('admin.roles.destroy', [$role->id]));

        $response->assertStatus(302);
        $this->assertTrue(Role::where('name', 'subscriber')->count() === 0);
    }
}
