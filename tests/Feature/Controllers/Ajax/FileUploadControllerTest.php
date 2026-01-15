<?php

namespace Tests\Feature\Controllers\Ajax;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    public function test_file_upload()
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

    public function test_image_upload()
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
