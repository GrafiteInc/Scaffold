<?php

use App\Notifications\InAppNotification;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Notification;

/*
 * --------------------------------------------------------------------------
 * Helpers for Notifications
 * --------------------------------------------------------------------------
*/

if (! function_exists('app_notify')) {
    function app_notify($message, $isImportant = false)
    {
        $notification = new InAppNotification($message);

        if ($isImportant) {
            $notification->isImportant();
        }

        auth()->user()->notify($notification);
    }
}

if (! function_exists('app_notify_user')) {
    function app_notify_user($user, $message, $isImportant = false)
    {
        $notification = new InAppNotification($message);

        if ($isImportant) {
            $notification->isImportant();
        }

        $user->notify($notification);
    }
}

if (! function_exists('email_notify')) {
    function email_notify($subject, $message)
    {
        $user = auth()->user();

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

if (! function_exists('email_notify_user')) {
    function email_notify_user($user, $subject, $message)
    {
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
