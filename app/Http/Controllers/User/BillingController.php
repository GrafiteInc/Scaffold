<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    /**
     * Billing settings
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe()
    {
        $user = auth()->user();

        if ($user->hasActiveSubscription()) {
            return redirect(route('user.billing.details'));
        }

        return view('user.billing.subscribe', [
            'user' => $user,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Billing renew
     *
     * @return \Illuminate\Http\Response
     */
    public function renewSubscription()
    {
        $user = auth()->user();

        return view('user.billing.renew', [
            'user' => $user,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Get subscription details
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscription()
    {
        $user = auth()->user();
        $invoice = $user->upcomingInvoice();
        $subscription = $user->subscription(config('billing.subscription_name'));

        return view('user.billing.details', [
            'user' => $user,
            'invoice' => $invoice,
            'subscription' => $subscription,
        ]);
    }

    /**
     * Change the payment method
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentMethod(Request $request)
    {
        $user = $request->user();

        return view('user.billing.payment-method', [
            'user' => $user,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Change subscription plan
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getChangePlan(Request $request)
    {
        $user = $request->user();

        return view('user.billing.change-plan', [
            'user' => $user,
        ]);
    }

    /**
     * Swap subscription plans
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function swapPlan(Request $request)
    {
        try {
            auth()->user()->subscription(config('billing.subscription_name'))->swap($request->plan);

            return redirect(route('user.billing.details'))->with('message', 'Your subscription was swapped!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return back()->withErrors(['Could not change your subscription, please try again.']);
    }

    /**
     * Add a coupon
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCoupon(Request $request)
    {
        $user = $request->user();

        return view('user.billing.coupon')
            ->with('user', $user);
    }

    /**
     * Apply a coupon
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyCoupon(Request $request)
    {
        try {
            auth()->user()->applyCoupon($request->coupon);

            return redirect(route('user.billing.details'))->with('message', 'Your coupon was used!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return back()->withErrors(['Could not process your coupon, please try again.']);
    }

    /**
     * Get invoices
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoices(Request $request)
    {
        $user = $request->user();
        $invoices = $user->invoices(config('billing.subscription_name'));

        return view('user.billing.invoices')
            ->with('invoices', $invoices)
            ->with('user', $user);
    }

    /**
     * Get one invoice
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
     * Cancel Subscription
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

            return redirect(route('user.billing.details'))->with('message', $message);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return back()->withErrors(['Could not cancel billing, please try again.']);
    }
}
