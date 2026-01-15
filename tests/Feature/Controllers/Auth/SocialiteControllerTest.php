<?php

namespace Tests\Feature\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use Tests\GuestTestCase;

class SocialiteControllerTest extends GuestTestCase
{
    public function test_redirect()
    {
        Socialite::fake('github');

        $response = $this->get('/auth/github/redirect');

        $response->assertRedirect();
    }
}
