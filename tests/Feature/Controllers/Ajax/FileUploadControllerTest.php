<?php

namespace Tests\Feature\Controllers\Ajax;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadControllerTest extends TestCase
{
    public function testFileUpload()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('ajax.files-upload'), [
            'file' => [$file],
        ]);

        $response->assertJson([
            'data' => [
                'success' => true,
            ],
        ]);

        $response->assertOk();
        Storage::assertExists('public/uploads/'.$file->hashName());
    }

    public function testImageUpload()
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('ajax.image-upload'), [
            'image' => $file,
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        $response->assertOk();
        Storage::assertExists('public/uploads/'.$file->hashName());
    }
}
