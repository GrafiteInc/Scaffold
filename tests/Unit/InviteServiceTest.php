<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Models\Invite;
use App\Services\InviteService;
use App\Notifications\UserInviteEmail;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

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

        $user = factory(User::class)->create();
        $message = 'This is a test message';

        $model = factory(Team::class)->create();

        $result = $this->service->create($model, $user->email, $message);

        $this->assertEquals($user->email, $result->email);

        Notification::assertSentTo(
            [$user],
            InAppNotification::class
        );

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            UserInviteEmail::class,
            function ($notification, $channels, $notifiable) use ($user) {
                return $notifiable->routes['mail'] === $user->email;
            }
        );
    }

    public function testValidateInvitation()
    {
        $user = factory(User::class)->create();
        $invite = factory(Invite::class)->create([
            'email' => $user->email,
        ]);

        $result = $this->service->validateInvitation($invite->token, $user->email);

        $this->assertTrue($result);
    }
}
