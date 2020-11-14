<?php

namespace App\Models\Concerns;

trait HasSubscribedUser
{
    /**
     * Check if the model user has an active subscription
     * in any possible state.
     *
     * @return bool
     */
    public function hasActiveSubscription()
    {
        return $this->user->hasActiveSubscription();
    }
}
