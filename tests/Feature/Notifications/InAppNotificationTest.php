<?php

namespace Tests\Feature\Notifications;

use App\Notifications\InAppNotification;
use NotificationChannels\WebPush\WebPushChannel;
use Tests\TestCase;

class InAppNotificationTest extends TestCase
{
    public function test_it_only_stores_in_the_database_without_a_device()
    {
        $notification = new InAppNotification('Hello');

        $this->assertEquals(['database'], $notification->via($this->user));
    }

    public function test_it_pushes_when_a_device_is_registered()
    {
        $this->user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc', 'key', 'token');

        $notification = new InAppNotification('Hello');

        $this->assertEquals(['database', WebPushChannel::class], $notification->via($this->user));
    }

    public function test_it_does_not_push_when_the_user_opted_out()
    {
        $this->user->updatePushSubscription('https://fcm.googleapis.com/fcm/send/abc', 'key', 'token');
        $this->user->update(['allow_push_notifications' => false]);

        $notification = new InAppNotification('Hello');

        $this->assertEquals(['database'], $notification->via($this->user->fresh()));
    }

    public function test_web_push_payload()
    {
        $notification = new InAppNotification('<strong>Task</strong> is due');
        $notification->isImportant();

        $message = $notification->toWebPush($this->user);
        $payload = $message->toArray();

        $this->assertEquals(config('app.name'), $payload['title']);
        $this->assertEquals('Task is due', $payload['body']);
        $this->assertEquals(route('user.notifications'), $payload['data']['url']);
        $this->assertEquals('high', $message->getOptions()['urgency']);
    }
}
