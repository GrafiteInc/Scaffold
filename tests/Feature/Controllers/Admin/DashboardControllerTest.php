<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();
    }
}
