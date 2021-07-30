<?php

namespace Tests\Feature\Controllers\Ajax;

use Tests\TestCase;

class ApiTokenControllerTest extends TestCase
{
    public function testCreate()
    {
        $response = $this->post(route('ajax.create-token'), [
            'name' => 'testing',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                ],
            ]);
    }

    public function testIndex()
    {
        $this->post(route('ajax.create-token'), [
            'name' => 'testing',
        ]);

        $response = $this->get(route('ajax.tokens'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'tokens',
                ],
            ]);
    }

    public function testDestroy()
    {
        $this->post(route('ajax.create-token'), [
            'name' => 'testing',
        ]);

        $response = $this->delete(route('ajax.destroy-token', [$this->user->tokens()->first()]));

        $response->assertStatus(200)
            ->assertJson(['message' => 'success']);
    }
}
