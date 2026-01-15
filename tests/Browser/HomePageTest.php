<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class HomePageTest extends DuskTestCase
{
    public function test_home_page()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                ->assertSee('Login')
                ->assertSee('Register');
        });
    }

    public function test_dashboard_access()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                ->visit('/dashboard')
                ->assertSee('Dashboard');
        });
    }
}
