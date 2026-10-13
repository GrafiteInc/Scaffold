<?php

namespace App\Listeners;

use App\Http\Controllers\User\PushSubscriptionController;
use Illuminate\Auth\Events\Logout;

class RemovePushSubscriptionOnLogout
{
    /**
     * Stop pushing to a browser once its user logs out, so the next person
     * to use it doesn't see the previous user's notifications.
     *
     * @return void
     */
    public function handle(Logout $event)
    {
        if (! $event->user || ! app()->bound('session')) {
            return;
        }

        $endpoint = session(PushSubscriptionController::SESSION_KEY);

        if ($endpoint && method_exists($event->user, 'deletePushSubscription')) {
            $event->user->deletePushSubscription($endpoint);
        }
    }
}
