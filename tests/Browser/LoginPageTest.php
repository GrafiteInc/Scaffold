<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginPageTest extends DuskTestCase
{
    public function test_login_process()
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
