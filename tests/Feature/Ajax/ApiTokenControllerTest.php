<?php

namespace Tests\Feature\Ajax;

use Tests\TestCase;

class ApiTokenControllerTest extends TestCase
{
    public function testReset()
    {
        $token = $this->user->api_token;

        $response = $this->post(route('ajax.reset-token'), []);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token'
                ],
            ]);

        $this->assertTrue($token != $this->user->fresh()->api_token);
    }
}
