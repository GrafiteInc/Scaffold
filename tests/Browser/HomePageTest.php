<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomePageTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Login')
                ->assertSee('Register');
        });
    }

    public function testDashboardAccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard')
                ->assertSee('Dashboard');
        });
    }
}
