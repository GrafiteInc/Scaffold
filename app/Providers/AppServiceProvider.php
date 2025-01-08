<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Laravel\Cashier\Cashier;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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

        Scramble::routes(function (Route $route) {
            return Str::startsWith($route->uri, 'api/');
        });

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
}
