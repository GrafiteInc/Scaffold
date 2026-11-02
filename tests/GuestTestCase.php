<?php

namespace Tests;

use App\Http\Middleware\Admin;
use App\Http\Middleware\Permissions;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class GuestTestCase extends BaseTestCase
{
    use RefreshDatabase;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            PreventRequestForgery::class,
            EnsureEmailIsVerified::class,
            Admin::class,
            Permissions::class,
        ]);
    }
}
