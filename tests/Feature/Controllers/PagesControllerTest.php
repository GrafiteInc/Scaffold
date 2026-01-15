<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class PagesControllerTest extends TestCase
{
    public function test_home()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_support()
    {
        $response = $this->get(route('support'));

        $response->assertOk();
    }

    public function test_privacy_policy()
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertOk();
    }

    public function test_terms_of_service()
    {
        $response = $this->get(route('terms-of-service'));

        $response->assertOk();
    }
}
