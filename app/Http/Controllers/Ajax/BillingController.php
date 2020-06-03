<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function createSubscription(Request $request)
    {
        try {
            $user = auth()->user();

            $user->update([
                'state' => $request->state,
                'country' => $request->country,
            ]);

            $user->newSubscription(config('billing.subscription_name'), $request->plan)
                ->create($request->payment_method);

            return response()->json([
                'message' => 'You\'re now subscribed!',
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'message' => 'Subscription failed',
        ], 409);
    }

    public function updatePaymentMethod(Request $request)
    {
        try {
            $user = auth()->user();

            $user->updateDefaultPaymentMethod($request->payment_method);

            return response()->json([
                'message' => 'Your payment method has been changed!',
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return response()->json([
            'message' => 'Card change failed',
        ], 409);
    }
}
