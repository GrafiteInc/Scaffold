<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('user.notifications'));

        $response->assertOk();
    }

    public function testMarkAsRead()
    {
        $this->markTestIncomplete();
    }

    public function testDelete()
    {
        $this->markTestIncomplete();
    }
}
