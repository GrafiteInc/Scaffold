<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Permissions;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class ApiTestCase extends BaseTestCase
{
    use CreatesApplication,
        RefreshDatabase;

    public $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            VerifyCsrfToken::class,
            EnsureEmailIsVerified::class,
            Admin::class,
            Permissions::class,
        ]);

        $role = factory(Role::class)->create([
            'name' => 'admin',
            'label' => 'Admin',
        ]);

        $this->user = factory(User::class)->create();
        $this->user->roles()->attach($role->id);

        $this->user->forceFill([
            'api_token' => hash('sha256', 'foo_bar_token'),
        ])->save();

        $this->be($this->user);
    }
}
