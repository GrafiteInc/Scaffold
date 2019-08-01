<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('admin.roles.index'));

        $response->assertOk();
        $response->assertSee('Admin');
    }
}
