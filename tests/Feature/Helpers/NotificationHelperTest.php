<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;
use App\Events\UserPusherEvent;
use App\Events\GeneralPusherEvent;
use Illuminate\Support\Facades\Event;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;

class NotificationHelperTest extends TestCase
{
    public function testAppNotify()
    {
        Notification::fake();

        app_notify('testing', true, $this->user);

        Notification::assertSentTo(
            [$this->user],
            InAppNotification::class
        );
    }

    public function testEmailNotifyDisabled()
    {
        Notification::fake();

        $this->user->update([
            'allow_email_based_notifications' => 0,
        ]);

        Notification::assertNothingSent();
    }

    public function testPusherGeneral()
    {
        Notification::fake();
        Event::fake();

        pusher_notify_general(['foo' => 'bar']);

        Event::assertDispatched(GeneralPusherEvent::class);
    }

    public function testPusherUser()
    {
        Notification::fake();
        Event::fake();

        pusher_notify_user($this->user, ['foo' => 'bar']);

        Event::assertDispatched(UserPusherEvent::class);
    }
}
