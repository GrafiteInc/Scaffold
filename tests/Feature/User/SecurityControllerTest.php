<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class SecurityControllerTest extends TestCase
{
    public function testSecurity()
    {
        $response = $this->get(route('user.security'));

        $response->assertOk();
    }
}
