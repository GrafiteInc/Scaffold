<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

trait HasSubscription
{
    /**
     * Check if the user has an active subscription
     * in any possible state.
     *
     * @return bool
     */
    public function hasActiveSubscription()
    {
        return Cache::remember($this->cacheIdentifier('subscription'), 300, function () {
            if ($this->subscription(config('billing.subscription_name')) && ! $this->subscription(config('billing.subscription_name'))->canceled()) {
                return true;
            }

            if (
                $this->subscription(config('billing.subscription_name')) &&
                $this->subscription(config('billing.subscription_name'))->canceled() &&
                $this->subscription(config('billing.subscription_name'))->onGracePeriod()
            ) {
                return true;
            }

            return false;
        });
    }

    /**
     * Check if the user subscription has been cancelled.
     *
     * @return bool
     */
    public function hasCanceledSubscription()
    {
        if ($this->subscription(config('billing.subscription_name'))) {
            return $this->subscription(config('billing.subscription_name'))->canceled();
        }

        return false;
    }

    /**
     * Get the users subscription plan.
     *
     * @param  string  $key
     * @return mixed
     */
    public function subscriptionPlan($key)
    {
        $plans = config('billing.plans');
        $plan = $this->subscription(config('billing.subscription_name'));

        if (! is_null($plan)) {
            $plan = $plan->stripe_price;

            return $plans[$plan][$key];
        }

        return null;
    }

    public function hasBillingInformation()
    {
        return
            ! is_null($this->billing_email)
            && ! is_null($this->state)
            && ! is_null($this->country);
    }
}
