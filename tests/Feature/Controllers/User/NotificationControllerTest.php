<?php

namespace Tests\Feature\Controllers\User;

use App\Models\DatabaseNotification;
use App\Models\User;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get(route('user.notifications'));

        $response->assertOk();
    }

    public function test_mark_as_read()
    {
        $notification = DatabaseNotification::factory()->create([
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
        ]);

        $response = $this->post(route('user.notifications.read', [$notification->id]));

        $response->assertStatus(302);
        $this->assertTrue(! is_null($notification->fresh()->read_at));
    }

    public function test_delete()
    {
        $notification = DatabaseNotification::factory()->create([
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
        ]);

        $response = $this->delete(route('user.notifications.destroy', [$notification->id]));

        $response->assertStatus(302);
        $this->assertEquals(0, DatabaseNotification::where('notifiable_id', $this->user->id)->count());
    }

    public function test_delete_all()
    {
        $notification = DatabaseNotification::factory(4)->create([
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
        ]);

        $response = $this->delete(route('user.notifications.clear'));

        $response->assertStatus(302);
        $this->assertEquals(0, DatabaseNotification::where('notifiable_id', $this->user->id)->count());
    }
}
