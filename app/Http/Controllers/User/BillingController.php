<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    /**
     * Get subscription details.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $upcomingPayment = null;

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethodFromStripe();

        if ($user->hasActiveSubscription()) {
            $upcomingPayment = $user->upcomingInvoice();
        }

        return view('user.billing')->with([
            'user' => $user,
            'upcomingPayment' => $upcomingPayment,
        ]);
    }

    public function success(Request $request)
    {
        $user = $request->user();

        $user->clearSubscriptionCache();

        activity('Subscribed.');

        return redirect()->route('user.billing')->withMessage('You\'re subscribed!');
    }

    public function cancelled(Request $request)
    {
        $user = $request->user();

        $user->clearSubscriptionCache();

        activity('Cancelled a subscription process.');

        return redirect()->route('user.billing')->withMessage('You cancelled! Let us know if you need help.');
    }

    public function subscribe(Request $request)
    {
        return $request->user()->checkout([$request->plan], [
            'success_url' => route('user.billing.subscribe.success'),
            'cancel_url' => route('user.billing.subscribe.cancelled'),
            'mode' => 'subscription',
        ]);
    }
}
