<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
    }
}
