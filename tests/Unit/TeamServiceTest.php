<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use App\Notifications\InAppNotification;
use App\Notifications\UserInviteEmail;
use App\Services\TeamService;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(TeamService::class);
    }

    public function test_create()
    {
        $result = $this->service->create([
            'name' => 'Justice League',
        ]);

        $this->assertEquals('Justice League', $result->name);
    }

    public function test_update()
    {
        $team = Team::factory()->create([
            'name' => 'Avengers',
        ]);

        $request = new \Illuminate\Http\Request([
            'name' => 'Justice League',
        ]);

        $result = $this->service->update($team, $request);

        $this->assertEquals('Justice League', $result->name);
    }

    public function test_invite()
    {
        Notification::fake();

        $team = Team::factory()->create([
            'name' => 'Avengers',
        ]);

        $result = $this->service->invite($team, 'someone@example.com');

        $this->assertStringContainsString('Avengers', $result->message);

        Notification::assertSentTo(new AnonymousNotifiable, UserInviteEmail::class);
    }

    public function test_leave_team()
    {
        Notification::fake();

        $teamAdmin = User::factory()->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = Team::factory()->create([
            'user_id' => 999,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($this->user->id);

        $this->service->leave($team);

        $this->assertEquals(0, $team->members()->count());

        Notification::assertSentTo($teamAdmin, InAppNotification::class);
    }

    public function test_remove_member()
    {
        $teamMember = User::factory()->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($teamMember);

        $this->service->remove($teamMember, $team);

        $this->assertEquals(0, $team->members()->count());
    }

    public function test_delete_team()
    {
        Notification::fake();

        $teamAdmin = User::factory()->create([
            'id' => 999,
            'name' => 'Joe',
        ]);

        $team = Team::factory()->create([
            'user_id' => 999,
            'name' => 'Avengers',
        ]);

        $team->members()->attach($this->user->id);

        $this->service->destroy($team);

        $this->assertEquals(0, $team->members()->count());

        Notification::assertSentTo($this->user, InAppNotification::class);
    }
}
