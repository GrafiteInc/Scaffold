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
        return Cache::remember($this->cacheIdentifier('subscription'), 86400, function () {
            if ($this->subscription(config('billing.subscription_name')) && ! $this->subscription(config('billing.subscription_name'))->cancelled()) {
                return true;
            }

            if (
                $this->subscription(config('billing.subscription_name')) &&
                $this->subscription(config('billing.subscription_name'))->cancelled() &&
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
    public function hasCancelledSubscription()
    {
        if ($this->subscription(config('billing.subscription_name'))) {
            return $this->subscription(config('billing.subscription_name'))->cancelled();
        }

        return false;
    }

    /**
     * Get the users subscription plan.
     *
     * @param string $key
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
}
