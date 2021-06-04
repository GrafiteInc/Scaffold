<?php

namespace Tests\Feature\Controllers\Ajax;

use Tests\GuestTestCase;

class CookiePolicyControllerTest extends GuestTestCase
{
    public function testAccept()
    {
        $response = $this->post(route('ajax.accept-cookie-policy'));

        $response->assertOk();
    }
}
