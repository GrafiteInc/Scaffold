<?php

namespace Tests\Feature\Controllers\Api;

use App\Notifications\StandardEmail;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\ApiTestCase;

class UsersControllerTest extends ApiTestCase
{
    public function testMe()
    {
        Sanctum::actingAs($this->user);

        $response = $this->get(route('api.users.me'));

        $content = json_decode($response->content());

        $this->assertEquals($this->user->name, $content->data->name);
    }

    public function testMeWithBearerToken()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->get(route('api.users.me'), [
            'Authorization' => "Bearer {$token}",
        ]);

        $content = json_decode($response->content());

        $this->assertEquals($this->user->name, $content->data->name);
    }

    public function testUpdate()
    {
        Sanctum::actingAs($this->user);

        $response = $this->putJson(route('api.users.update'), [
            'name' => 'Burt Cooper',
            'email' => $this->user->email,
        ]);

        $content = json_decode($response->content());

        $this->assertEquals('Burt Cooper', $content->data->name);
        $this->assertEquals($this->user->email, $content->data->email);
    }

    public function testDestroy()
    {
        Sanctum::actingAs($this->user);
        Storage::fake();
        Notification::fake();

        $response = $this->delete(route('api.users.destroy'), []);

        $response->assertJson([
            'status' => 'Profile deleted',
        ]);

        $user = $this->user;

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            StandardEmail::class,
            function ($notification, $channels, $notifiable) use ($user) {
                return $notifiable->routes['mail'] === $user->email;
            }
        );
    }
}
