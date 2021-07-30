<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Invite;
use App\Notifications\StandardEmail;
use App\Notifications\UserInviteEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class InvitesControllerTest extends TestCase
{
    public function testResend()
    {
        Notification::fake();

        $invite = Invite::factory()->create();

        $response = $this->post(route('invite.resend', [$invite->id]));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            UserInviteEmail::class,
            function ($notification, $channels, $notifiable) use ($invite) {
                return $notifiable->routes['mail'] === $invite->email;
            }
        );

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Invitation was resent');
    }

    public function testRevoke()
    {
        Notification::fake();

        $invite = Invite::factory()->create();

        $response = $this->post(route('invite.revoke', [$invite->id]));

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            StandardEmail::class,
            function ($notification, $channels, $notifiable) use ($invite) {
                return $notifiable->routes['mail'] === $invite->email;
            }
        );

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Invitation was revoked');
    }
}
