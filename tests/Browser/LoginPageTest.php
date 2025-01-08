<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginPageTest extends DuskTestCase
{
    public function testLoginProcess()
    {
        $this->browse(function ($browser) {
            $browser->waitForReload(function (Browser $browser) {
                $browser->visit('/login')
                    ->type('email', $this->user->email)
                    ->type('password', 'secret')
                    ->press('Login');
                })
                ->assertPathIs('/dashboard')
                ->assertAuthenticated();
        });
    }
}
