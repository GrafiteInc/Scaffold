<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\InAppNotification;

class BillingController extends Controller
{
    /**
     * Billing settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $user = $request->user();

        if ($user->hasActiveSubscription()) {
            return redirect()->route('user.billing.details');
        }

        $intent = $user->createSetupIntent();

        return view('user.billing.subscribe')->with(compact('user', 'intent'));
    }

    /**
     * Billing renew.
     *
     * @return \Illuminate\Http\Response
     */
    public function renewSubscription(Request $request)
    {
        $user = $request->user();
        $intent = $user->createSetupIntent();

        return view('user.billing.renew')->with(compact('user', 'intent'));
    }

    /**
     * Get subscription details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscription(Request $request)
    {
        $user = $request->user();
        $invoice = $user->upcomingInvoice();
        $subscription = $user->subscription(config('billing.subscription_name'));

        return view('user.billing.details')->with(compact('user', 'invoice', 'subscription'));
    }

    /**
     * Change the payment method.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentMethod(Request $request)
    {
        $user = $request->user();
        $intent = $user->createSetupIntent();

        return view('user.billing.payment-method')->with(compact('user', 'intent'));
    }

    /**
     * Change subscription plan.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getChangePlan(Request $request)
    {
        $user = $request->user();

        return view('user.billing.change-plan')->with(compact('user'));
    }

    /**
     * Swap subscription plans.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function swapPlan(Request $request)
    {
        try {
            $request->user()->subscription(config('billing.subscription_name'))->swap($request->plan);

            return redirect()->route('user.billing.details')->withMessage('Your subscription was swapped!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()->withErrors(['Could not change your subscription, please try again.']);
    }

    /**
     * Add a coupon.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCoupon(Request $request)
    {
        $user = $request->user();

        return view('user.billing.coupon')->with(compact('user'));
    }

    /**
     * Apply a coupon.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyCoupon(Request $request)
    {
        try {
            $request->user()->applyCoupon($request->coupon);

            return redirect()->route('user.billing.details')->withMessage('Your coupon was used!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()->withErrors(['Could not process your coupon, please try again.']);
    }

    /**
     * Get invoices.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoices(Request $request)
    {
        $user = $request->user();
        $invoices = $user->invoices(config('billing.subscription_name'));

        return view('user.billing.invoices')->with(compact('user', 'invoices'));
    }

    /**
     * Get one invoice.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoiceById($id, Request $request)
    {
        try {
            $user = $request->user();

            $response = $user->downloadInvoice($id, [
                'vendor' => config('billing.invoice.company'),
                'street' => config('billing.invoice.street'),
                'location' => config('billing.invoice.location'),
                'phone' => config('billing.invoice.phone'),
                'url' => config('billing.invoice.url'),
                'product' => config('billing.invoice.product'),
                'description' => 'Subscription',
            ]);
        } catch (Exception $e) {
            $response = back()->withErrors(['Could not find this invoice, please try again.']);
        }

        return $response;
    }

    /**
     * Cancel Subscription.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSubscription(Request $request)
    {
        try {
            $user = $request->user();
            $invoice = $user->upcomingInvoice();

            $user->subscription(config('billing.subscription_name'))->cancel();

            $date = $invoice->date()->format('Y-m-d');
            $message = 'Your subscription has been cancelled! It will be availale until ' . $date;

            $notification = new InAppNotification($message);
            $notification->isImportant();
            $user->notify($notification);

            return redirect()->route('user.billing.details')->withMessage($message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()->withErrors(['Could not cancel billing, please try again.']);
    }
}
