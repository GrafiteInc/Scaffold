<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
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
}
