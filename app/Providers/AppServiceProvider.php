<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Cashier\Cashier;
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

        Blade::directive('session', function ($nonce) {
            return '<?php echo javascript_session_data(' . $nonce . '); ?>';
        });

        if (! app()->environment('testing')) {
            URL::forceScheme('https');
        }
    }
}
