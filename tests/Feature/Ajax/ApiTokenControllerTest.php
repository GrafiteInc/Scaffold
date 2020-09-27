<?php

namespace Tests\Feature\Ajax;

use Tests\TestCase;

class ApiTokenControllerTest extends TestCase
{
    public function testCreate()
    {
        $response = $this->post(route('ajax.create-token'), [
            'name' => 'testing'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ],
            ]);
    }
}
