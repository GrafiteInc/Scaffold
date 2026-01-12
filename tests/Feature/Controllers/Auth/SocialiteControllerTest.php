<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\GuestTestCase;
use Laravel\Socialite\Facades\Socialite;

class SocialiteControllerTest extends GuestTestCase
{
    public function testRedirect()
    {
        Socialite::fake('github');

        $response = $this->get('/auth/github/redirect');

        $response->assertRedirect();
    }
}
