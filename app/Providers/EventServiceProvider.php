<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Listeners\SamlAssertionAttributes;
use CodeGreenCreative\SamlIdp\Events\Assertion;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Assertion::class => [
            SamlAssertionAttributes::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
