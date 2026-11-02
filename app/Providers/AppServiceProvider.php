<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Cashier\Cashier;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::calculateTaxes();
        Cashier::keepPastDueSubscriptionsActive();
        Cashier::useCustomerModel(User::class);

        Password::defaults(fn () => Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols()
            ->uncompromised());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Schedule::alwaysOnOneServer();

        $this->defineGates();

        Scramble::routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        });

        // Passport only backs the OAuth flow for MCP clients (see routes/ai.php).
        Passport::authorizationView(fn ($parameters) => view('mcp.authorize', $parameters));
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(90));

        // Gate::define('viewApiDocs', function (User $user) {
        //     return in_array($user->email, ['admin@app.com']);
        // });

        Blade::if('permission', function ($value) {
            return request()->user()->hasPermission($value);
        });

        Blade::directive('nonce', function () {
            return '';
        });

        if (! app()->environment('testing')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register the application's authorization gates.
     */
    protected function defineGates(): void
    {
        // Gateway for determining if user is admin
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin');
        });

        // Gateway for determining if user has permissions
        Gate::define('has-permissions', function ($user, $requestPermissionCollection) {
            $requestPermissions = explode(',', $requestPermissionCollection);

            foreach ($requestPermissions as $accessPermission) {
                if (in_array($accessPermission, $user->permissions->toArray())) {
                    return true;
                }
            }

            return false;
        });

        // Gateway for determining team admin
        Gate::define('team-admin', function ($user, $team) {
            return (int) $user->id === (int) $team->user_id;
        });

        // Gateway for determining team manager
        Gate::define('team-manager', function ($user, $team) {
            if ((int) $user->id === (int) $team->user_id) {
                return true;
            }

            $member = $team->members->find($user->id);

            // Membership levels
            if (
                in_array($member->membership->team_role, [
                    'manager',
                ])
            ) {
                return true;
            }

            return false;
        });

        // Gateway for determining team members
        Gate::define('team-member', function ($user, $team) {
            if ((int) $user->id === (int) $team->user_id) {
                return true;
            }

            return $team->members->contains($user->id);
        });

        // Gateway for determining subscribers
        Gate::define('subscribed', function ($user) {
            return $user->hasActiveSubscription() || $user->onTrial() || $user->hasRole('admin');
        });

        // Gateway for determining not cancelled subscribers
        Gate::define('subscription-not-canceled', function ($user) {
            return ! $user->hasCanceledSubscription();
        });

        // Gateway for determining not cancelled subscribers
        Gate::define('subscription-canceled', function ($user) {
            return $user->hasCanceledSubscription();
        });
    }
}
