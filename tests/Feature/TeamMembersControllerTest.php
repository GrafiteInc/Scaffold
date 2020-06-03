<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamMembersControllerTest extends TestCase
{
    public function testShow()
    {
        $team = factory(Team::class)->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('teams.show', [$team->uuid]));

        $response->assertOk();
    }

    public function testEditMember()
    {
        $team = factory(Team::class)->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $member = factory(User::class)->create();

        $team->members()->attach($member);

        $response = $this->get(route('teams.members.edit', [$team->id, $member->id]));

        $response->assertOk();
    }

    public function testUpdateMember()
    {
        $team = factory(Team::class)->create([
            'name' => 'Avengers',
            'user_id' => $this->user->id,
        ]);

        $member = factory(User::class)->create();

        $team->members()->attach($member);

        $response = $this->put(route('teams.members.update', [$team->id, $member->id]), [
            'team_role' => 'manager',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully updated');
    }

    public function testInviteMember()
    {
        Notification::fake();

        $team = factory(Team::class)->create([
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
        Notification::fake();

        $team = factory(Team::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->user->teamMemberships()->attach($team);

        $response = $this->post(route('teams.leave', [$team->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Success, your membership was removed');
    }

    public function testRemoveTeamMember()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $this->user->id,
        ]);

        $user->teamMemberships()->attach($team);

        $response = $this->delete(route('teams.members.remove', [$team->id, $user->id]));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Success, the member was removed');
    }
}
