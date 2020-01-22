<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AccountService;

class AccountServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(AccountService::class);
    }

    public function testAccountService()
    {
        $this->markTestIncomplete();
    }
}
