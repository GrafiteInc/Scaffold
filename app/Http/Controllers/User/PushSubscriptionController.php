<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PushSubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Session key holding this browser's push endpoint, so logging out
     * can drop the subscription (see RemovePushSubscriptionOnLogout).
     */
    public const SESSION_KEY = 'push_endpoint';

    /**
     * Register (or re-sync) this browser's push subscription.
     *
     * @return JsonResponse
     */
    public function store(PushSubscriptionRequest $request)
    {
        $request->user()->updatePushSubscription(
            $request->input('endpoint'),
            $request->input('keys.p256dh'),
            $request->input('keys.auth'),
            $request->input('contentEncoding'),
        );

        $request->session()->put(self::SESSION_KEY, $request->input('endpoint'));

        return response()->json(['message' => 'Push notifications enabled on this device.']);
    }

    /**
     * Remove this browser's push subscription.
     *
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string|max:1024',
        ]);

        $request->user()->deletePushSubscription($request->input('endpoint'));

        $request->session()->forget(self::SESSION_KEY);

        return response()->json(['message' => 'Push notifications disabled on this device.']);
    }

    /**
     * Send a test notification to every device the user has registered.
     *
     * @return JsonResponse
     */
    public function test(Request $request)
    {
        $user = $request->user();

        if (! $user->allow_push_notifications) {
            return response()->json(['message' => 'Push notifications are turned off in your settings.'], 422);
        }

        if (! $user->pushSubscriptions()->exists()) {
            return response()->json(['message' => 'No devices are registered for push notifications.'], 422);
        }

        app_notify('Push notifications are working on this device.', false, $user);

        return response()->json(['message' => 'Test notification sent.']);
    }
}
