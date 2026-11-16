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

    public function test_update_settings_rejects_disposable_email()
    {
        $response = $this->put(route('user.update'), [
            'name' => 'Burt Cooper',
            'email' => 'burt@10minutemail.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertNotEquals('burt@10minutemail.com', $this->user->fresh()->email);
    }

    public function test_update_settings_keeps_existing_disposable_email()
    {
        $this->user->forceFill(['email' => 'burt@10minutemail.com'])->save();

        $response = $this->put(route('user.update'), [
            'name' => 'Burt Cooper',
            'email' => 'burt@10minutemail.com',
        ]);

        $response->assertSessionHasNoErrors();
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
