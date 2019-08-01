<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class InvitesControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('user.invites'));

        $response->assertOk();
    }
}
