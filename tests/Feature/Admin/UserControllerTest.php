<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Invite New User');
    }

    public function testInvite()
    {
        $this->markTestIncomplete();
    }

    public function testEdit()
    {
        $this->markTestIncomplete();
    }

    public function testUpdate()
    {
        $this->markTestIncomplete();
    }

    public function testDelete()
    {
        $this->markTestIncomplete();
    }
}
