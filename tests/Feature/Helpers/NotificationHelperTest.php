<?php

namespace Tests\Feature\Helpers;

use App\Events\GeneralPusherEvent;
use App\Events\UserPusherEvent;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationHelperTest extends TestCase
{
    public function test_app_notify()
    {
        Notification::fake();

        app_notify('testing', true, $this->user);

        Notification::assertSentTo(
            [$this->user],
            InAppNotification::class
        );
    }

    public function test_email_notify_disabled()
    {
        Notification::fake();

        $this->user->update([
            'allow_email_based_notifications' => 0,
        ]);

        Notification::assertNothingSent();
    }

    public function test_pusher_general()
    {
        Notification::fake();
        Event::fake();

        pusher_notify_general(['foo' => 'bar']);

        Event::assertDispatched(GeneralPusherEvent::class);
    }

    public function test_pusher_user()
    {
        Notification::fake();
        Event::fake();

        pusher_notify_user($this->user, ['foo' => 'bar']);

        Event::assertDispatched(UserPusherEvent::class);
    }
}
