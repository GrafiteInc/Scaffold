<?php

use App\Notifications\InAppNotification;

/*
 * --------------------------------------------------------------------------
 * Helpers for Notifications
 * --------------------------------------------------------------------------
*/

if (!function_exists('app_notification')) {
    function app_notification($description, $isImportant = false)
    {
        $notification = new InAppNotification($description);

        if ($isImportant) {
            $notification->isImportant();
        }

        auth()->user()->notify($notification);
    }
}
