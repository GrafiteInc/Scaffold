<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class HomePageTest extends DuskTestCase
{
    public function testHomePage()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                ->assertSee('Login')
                ->assertSee('Register');
        });
    }

    public function testDashboardAccess()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                ->visit('/dashboard')
                ->assertSee('Dashboard');
        });
    }
}
