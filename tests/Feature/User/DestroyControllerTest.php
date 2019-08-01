<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class DestroyControllerTest extends TestCase
{
    public function testDestroy()
    {
        $response = $this->delete(route('user.destroy'));

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
