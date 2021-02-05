<?php

namespace App\Http\Controllers\Ajax;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use App\Notifications\InAppNotification;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    public function createSubscription(Request $request)
    {
        $message = 'Subscription failed';
        $redirect = null;

        try {
            $user = $request->user();

            $user->update([
                'state' => $request->state,
                'billing_email' => $request->email,
                'country' => $request->country,
            ]);

            $user->newSubscription(config('billing.subscription_name'), $request->plan)
                ->create($request->payment_method);

            $plan = config("billing.plans.{$request->plan}.name");
            $notification = new InAppNotification("You're now subscribed on the {$plan} plan.");
            $notification->isImportant();
            $user->notify($notification);

            activity("Subscribed to {$plan} plan.");

            Cache::forget($user->cacheIdentifier('subscription'));

            return response()->json([
                'message' => 'You\'re now subscribed!',
            ]);
        } catch (IncompletePayment $exception) {
            $message = 'Subscription requires extra steps.';
            $redirect = route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route(RouteServiceProvider::HOME)]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ], 409);
    }

    public function updatePaymentMethod(Request $request)
    {
        try {
            $user = $request->user();

            $user->updateDefaultPaymentMethod($request->payment_method);

            $notification = new InAppNotification("Your payment method has been updated to a card ending in {$user->card_last_four}.");
            $notification->isImportant();
            $user->notify($notification);

            activity("Payment method updated to card ending in {$user->card_last_four}.");

            return response()->json([
                'message' => 'Your payment method has been updated!',
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'message' => 'Card change failed',
        ], 409);
    }
}
