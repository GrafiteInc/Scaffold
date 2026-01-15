<?php

namespace Tests\Feature\Controllers\User;

use Tests\TestCase;

class ApiTokenControllerTest extends TestCase
{
    public function test_create()
    {
        $response = $this->post(route('user.create-token'), [
            'name' => 'testing',
        ]);

        $response->assertStatus(302);
    }

    public function test_index()
    {
        $this->post(route('user.create-token'), [
            'name' => 'testing',
        ]);

        $response = $this->get(route('user.api-tokens'));

        $response->assertStatus(200);
    }

    public function test_destroy()
    {
        $this->post(route('user.create-token'), [
            'name' => 'testing',
        ]);

        $response = $this->delete(route('user.destroy-token', [$this->user->tokens()->first()]));

        $response->assertStatus(302);
    }
}
