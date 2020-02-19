<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use App\Notifications\UserInviteEmail;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class TeamServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(TeamService::class);
    }

    public function testCreate()
    {
        $result = $this->service->create([
            'name' => 'Justice League'
        ]);

        $this->assertEquals('Justice League', $result->name);
    }

    public function testUpdate()
    {
        $team = factory(Team::class)->create([
            'name' => 'Avengers'
        ]);

        $result = $this->service->update($team, [
            'name' => 'Justice League'
        ]);

        $this->assertEquals('Justice League', $result->name);
    }

    public function testInvite()
    {
        Notification::fake();

        $team = factory(Team::class)->create([
            'name' => 'Avengers'
        ]);

        $result = $this->service->invite($team->id, 'someone@example.com');

        $this->assertStringContainsString('Avengers', $result->message);

        Notification::assertSentTo(new AnonymousNotifiable(), UserInviteEmail::class);
    }

    public function testLeaveTeam()
    {
        Notification::fake();

        $teamAdmin = factory(User::class)->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = factory(Team::class)->create([
            'user_id' => 999,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($this->user->id);

        $this->service->leave($team->id);

        $this->assertEquals(0, $team->members()->count());

        Notification::assertSentTo($teamAdmin, InAppNotification::class);
    }

    public function testRemoveMember()
    {
        $teamMember = factory(User::class)->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = factory(Team::class)->create([
            'user_id' => $this->user->id,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($teamMember);

        $this->service->remove($teamMember, $team->id);

        $this->assertEquals(0, $team->members()->count());
    }

    public function testDeleteTeam()
    {
        Notification::fake();

        $teamAdmin = factory(User::class)->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = factory(Team::class)->create([
            'user_id' => 999,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($this->user->id);

        $this->service->destroy($team);

        $this->assertEquals(0, $team->members()->count());

        Notification::assertSentTo($this->user, InAppNotification::class);
    }
}
