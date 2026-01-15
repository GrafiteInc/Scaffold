<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_dashboard()
    {
        $response = $this->get(route('dashboard'));

        $response->assertOk();
    }
}
