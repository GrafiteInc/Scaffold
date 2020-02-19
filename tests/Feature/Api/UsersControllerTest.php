<?php

namespace Tests\Feature\Api;

use Tests\ApiTestCase;
use App\Notifications\StandardEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class UsersControllerTest extends ApiTestCase
{
    public function testMe()
    {
        $response = $this->get(route('api.users.me'), [
            'Authorization' => 'Bearer foo_bar_token'
        ]);

        $content = json_decode($response->content());

        $this->assertEquals($this->user->name, $content->data->name);
    }

    public function testUpdate()
    {
        $response = $this->putJson(route('api.users.update'), [
            'name' => 'Burt Cooper',
            'email' => $this->user->email
        ], [
            'Authorization' => 'Bearer foo_bar_token'
        ]);

        $content = json_decode($response->content());

        $this->assertEquals('Burt Cooper', $content->data->name);
        $this->assertEquals($this->user->email, $content->data->email);
    }

    public function testDestroy()
    {
        Storage::fake();
        Notification::fake();

        $response = $this->delete(route('api.users.destroy'), [], [
            'Authorization' => 'Bearer foo_bar_token'
        ]);

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
