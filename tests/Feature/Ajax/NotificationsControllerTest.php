<?php

namespace Tests\Feature\Ajax;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsControllerTest extends TestCase
{
    public function testGetCount()
    {
        $notification = factory(DatabaseNotification::class, 4)->create([
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
