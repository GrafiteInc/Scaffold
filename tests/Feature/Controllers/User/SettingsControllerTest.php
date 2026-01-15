<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    public function test_settings()
    {
        $response = $this->get(route('user.settings'));

        $response->assertOk();
    }

    public function test_update_settings()
    {
        $response = $this->put(route('user.update'), [
            'name' => 'Burt Cooper',
            'email' => 'burt@sterlingcooperdraperprice.com',
            'dark_mode' => true,
        ]);

        $response->assertStatus(302);
        $this->assertEquals('Burt Cooper', $this->user->fresh()->name);
    }

    public function test_update_user_avatar()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->put(route('user.update'), [
            'name' => 'Burt Cooper',
            'email' => 'burt@sterlingcooperdraperprice.com',
            'avatar' => $file,
        ]);

        $response->assertStatus(302);

        $this->assertEquals('Burt Cooper', $this->user->fresh()->name);
        $this->assertTrue(! is_null($this->user->fresh()->avatar));
    }
}
