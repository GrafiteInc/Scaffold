<?php

namespace Tests\Feature\Controllers\Ajax;

use Tests\TestCase;
use App\Models\User;
use App\Models\DatabaseNotification;

class NotificationsControllerTest extends TestCase
{
    public function testGetCount()
    {
        DatabaseNotification::factory(4)->create([
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
        ]);

        $response = $this->get(route('ajax.notifications-count'));

        $response->assertStatus(200)
            ->assertJson([
                'data' => 4,
            ]);
    }
}
