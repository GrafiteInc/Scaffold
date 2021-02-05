<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Gate;

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
        return Gate::forUser($this->user)->allows('subscribed');
    }
}
