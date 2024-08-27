<?php

namespace Tests\Unit;

use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InAppNotification;
use App\Notifications\UserInviteEmail;
use App\Services\InviteService;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InviteServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(InviteService::class);
    }

    public function testCreate()
    {
        Notification::fake();

        $user = User::factory()->create();
        $message = 'This is a test message';

        $model = Team::factory()->create();

        $result = $this->service->create($model, $user->email, $message);

        $this->assertEquals($user->email, $result->email);

        Notification::assertSentTo(
            [$user],
            InAppNotification::class
        );

        Notification::assertSentTo(
            new AnonymousNotifiable,
            UserInviteEmail::class,
            function ($notification, $channels, $notifiable) use ($user) {
                return $notifiable->routes['mail'] === $user->email;
            }
        );
    }

    public function testValidateInvitation()
    {
        $user = User::factory()->create();
        $invite = Invite::factory()->create([
            'email' => $user->email,
        ]);

        $result = $this->service->validateInvitation($invite->token, $user->email);

        $this->assertTrue($result);
    }
}
