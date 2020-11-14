<?php

namespace Tests;

use Mockery;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Permissions;
use App\Http\Middleware\VerifyCsrfToken;
use Laravel\Cashier\SubscriptionBuilder;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            VerifyCsrfToken::class,
            EnsureEmailIsVerified::class,
            Admin::class,
            Permissions::class,
            RequirePassword::class,
        ]);

        $role = Role::factory()->create([
            'name' => 'admin',
            'label' => 'Admin',
        ]);

        $this->user = User::factory()->create();
        $this->user->roles()->attach($role->id);

        $this->be($this->user);
    }

    public function withSubscription()
    {
        return $this->user->subscriptions()->create([
            'name' => 'main',
            'stripe_id' => Str::random(),
            'stripe_status' => 'active',
            'stripe_plan' => 'foo-bar',
            'quantity' => 1,
        ]);
    }
}
