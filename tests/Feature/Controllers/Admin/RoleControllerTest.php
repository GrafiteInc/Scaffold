<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\Role;

class RoleControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertSee('Admin');
        $response->assertSee('Roles');
    }

    public function testCreate()
    {
        $response = $this->get(route('admin.roles.create'));

        $response->assertOk();
        $response->assertSee('Label');
        $response->assertSee('Permissions');
    }

    public function testStore()
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

    public function testEdit()
    {
        $role = Role::factory()->create();

        $response = $this->get(route('admin.roles.edit', [$role->id]));

        $response->assertOk();
        $response->assertSee('Label');
        $response->assertSee('Permissions');
    }

    public function testUpdate()
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

    public function testDelete()
    {
        $role = Role::factory()->create([
            'label' => 'Subscriber',
        ]);

        $response = $this->delete(route('admin.roles.destroy', [$role->id]));

        $response->assertStatus(302);
        $this->assertTrue(Role::where('name', 'subscriber')->count() === 0);
    }
}
