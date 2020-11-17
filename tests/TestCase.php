<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Permissions;
use App\Http\Middleware\VerifyCsrfToken;
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
        return $this->user->subscriptions()->firstOrCreate([
            'name' => config('billing.subscription_name'),
            'stripe_id' => 'foo-bar-test-subscription-id',
            'stripe_status' => 'active',
            'stripe_plan' => 'foo-bar',
            'quantity' => 1,
        ]);
    }
}
