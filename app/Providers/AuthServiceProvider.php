<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Gateway for determining if user is admin
         */
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin');
        });

        /**
         * Gateway for determining team admin
         */
        Gate::define('team-admin', function ($user, $team) {
            return $user->id == $team->user_id;
        });

        /**
         * Gateway for determining team members
         */
        Gate::define('team-member', function ($user, $team) {
            return $team->members->contains($user->id);
        });

        /**
         * Gateway for determining subscribers
         */
        Gate::define('subscribed', function ($user) {
            return $user->hasActiveSubscription();
        });

        /**
         * Gateway for determining not cancelled subscribers
         */
        Gate::define('subscription-not-cancelled', function ($user) {
            return !$user->hasCancelledSubscription();
        });

        /**
         * Gateway for determining not cancelled subscribers
         */
        Gate::define('subscription-cancelled', function ($user) {
            return $user->hasCancelledSubscription();
        });
    }
}
