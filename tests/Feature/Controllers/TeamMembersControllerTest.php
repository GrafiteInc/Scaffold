<?php

namespace Tests\Feature\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamMembersControllerTest extends TestCase
{
    public function testShow()
    {
        $this->withSubscription();

        $team = Team::factory()->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('teams.show', [$team->uuid]));

        $response->assertOk();
    }

    public function testEditMember()
    {
        $this->withSubscription();

        $team = Team::factory()->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $member = User::factory()->create();

        $team->members()->attach($member);

        $response = $this->get(route('teams.members.edit', [$team->id, $member->id]));

        $response->assertOk();
    }

    public function testUpdateMember()
    {
        $this->withSubscription();

        $team = Team::factory()->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $member = User::factory()->create();

        $team->members()->attach($member);

        $response = $this->put(route('teams.members.update', [$team->id, $member->id]), [
            'team_role' => 'manager',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully updated');
    }

    public function testInviteMember()
    {
        $this->withSubscription();

        Notification::fake();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->post(route('teams.members.invite', [$team->id]), [
            'email' => 'burt@sterlingcooperdraperprice.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully sent invite');
    }

    public function testLeaveTeam()
    {
        $this->withSubscription();

        Notification::fake();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->memberships()->attach($team);

        $response = $this->post(route('teams.leave', [$team->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Success, your membership was removed');
    }

    public function testRemoveTeamMember()
    {
        $this->withSubscription();

        Notification::fake();

        $user = User::factory()->create();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $user->memberships()->attach($team);

        $response = $this->delete(route('teams.members.remove', [$team->id, $user->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Success, the member was removed');
    }
}
