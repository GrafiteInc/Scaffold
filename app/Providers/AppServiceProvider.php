<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Cashier\Cashier;

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
