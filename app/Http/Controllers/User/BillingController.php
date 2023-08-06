<?php

namespace App\Http\Controllers\User;

use Exception;
use App\View\Forms\SwapForm;
use Illuminate\Http\Request;
use App\View\Forms\CouponForm;
use App\View\Forms\BillingForm;
use App\View\Forms\SubscribeForm;
use Illuminate\Support\Facades\Log;
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

        $form = app(BillingForm::class)->setUser($user)->make();
        $subscribeForm = app(SubscribeForm::class)->setUser($user)->make();
        $swapForm = app(SwapForm::class)->setUser($user)->make();
        $couponForm = app(CouponForm::class)->make();

        if ($user->hasActiveSubscription()) {
            $upcomingPayment = $user->upcomingInvoice();
        }

        return view('user.billing')->with([
            'user' => $user,
            'form' => $form,
            'subscribeForm' => $subscribeForm,
            'swapForm' => $swapForm,
            'couponForm' => $couponForm,
            'upcomingPayment' => $upcomingPayment,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $user->update([
            'billing_email' => $request->billing_email,
            'state' => $request->state,
            'country' => $request->country,
        ]);

        activity("Updated billing information.");

        $user->updateStripeCustomer([
            'email' => $request->billing_email,
            'address' => [
                'state' => $request->state,
                'country' => $request->country,
            ],
        ]);

        return redirect()->back()->withMessage('Information updated.');
    }

    public function subscribe(Request $request)
    {
        try {
            $paymentMethod = $request->user()->defaultPaymentMethod();
            $plan = $request->plan;

            $request->user()->newSubscription(
                'main', $plan
            )->create($paymentMethod->id);

            activity("Subscribed to {$request->plan} subscription plan.");

            return redirect()
                ->route('user.billing')
                ->withMessage('You\'re subscribed!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()
            ->withErrors(['Could not set your subscription, please try again.']);
    }

    /**
     * Change subscription plan.
     *
     * @return \Illuminate\View\View
     */
    public function getChangePlan(Request $request)
    {
        $user = $request->user();

        return view('user.billing.change-plan')->with(compact('user'));
    }

    /**
     * Swap subscription plans.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function swap(Request $request)
    {
        try {
            $request->user()
                ->subscription(config('billing.subscription_name'))
                ->swap($request->plan);

            activity("Switched to {$request->plan} subscription plan.");

            return redirect()->route('user.billing')
                ->withMessage('Your subscription was swapped!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()
            ->withErrors(['Could not change your subscription, please try again.']);
    }

    /**
     * Apply a coupon.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function coupon(Request $request)
    {
        try {
            $coupon = $request->user()->findPromotionCode($request->coupon)->coupon();

            $request->user()
                ->applyCoupon($coupon->id);

            activity("Used coupon: {$request->coupon}.");

            return redirect()->route('user.billing')
                ->withMessage('Your coupon was used!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return redirect()->back()->withErrors(['Could not process your coupon, please try again.']);
    }
}
