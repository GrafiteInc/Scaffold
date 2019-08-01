<?php

namespace Tests\Feature\User;

use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    public function testSettings()
    {
        $response = $this->get(route('user.settings'));

        $response->assertOk();
    }
}
