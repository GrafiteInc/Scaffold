<?php

use App\Events\GeneralPusherEvent;
use App\Events\UserPusherEvent;
use App\Notifications\InAppNotification;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Notification;

/*
 * --------------------------------------------------------------------------
 * Helpers for Notifications
 * --------------------------------------------------------------------------
*/

if (! function_exists('app_notify')) {
    function app_notify($message, $isImportant = false, $user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        $notification = new InAppNotification($message);

        if ($isImportant) {
            $notification->isImportant();
        }

        $user->notify($notification);
    }
}

if (! function_exists('email_notify')) {
    function email_notify($subject, $message, $user = null)
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        if ($user->allow_email_based_notifications) {
            Notification::route('mail', $user->email)
                ->notify(new StandardEmail(
                    $user->name,
                    $subject,
                    $message
                ));
        }
    }
}

if (! function_exists('pusher_notify_general')) {
    function pusher_notify_general($data)
    {
        event(new GeneralPusherEvent($data));
    }
}

if (! function_exists('pusher_notify_user')) {
    function pusher_notify_user($user, $data)
    {
        event(new UserPusherEvent($user, $data));
    }
}
