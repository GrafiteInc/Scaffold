<?php

namespace App\Models\Concerns;

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
    }

    /**
     * Check if the user subscription has been cancelled.
     *
     * @return bool
     */
    public function hasCancelledSubscription()
    {
        return $this->subscription(config('billing.subscription_name'))->cancelled();
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
        $plan = $this->subscription(config('billing.subscription_name'))->stripe_plan;

        return $plans[$plan][$key];
    }
}
