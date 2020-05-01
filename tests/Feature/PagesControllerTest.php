<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesControllerTest extends TestCase
{
    public function testHome()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function testContact()
    {
        $response = $this->get(route('contact'));

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
