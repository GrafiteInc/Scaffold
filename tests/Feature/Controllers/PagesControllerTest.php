<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class PagesControllerTest extends TestCase
{
    public function testHome()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function testSupport()
    {
        $response = $this->get(route('support'));

        $response->assertOk();
    }

    public function testPrivacyPolicy()
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertOk();
    }

    public function testTermsOfService()
    {
        $response = $this->get(route('terms-of-service'));

        $response->assertOk();
    }
}
