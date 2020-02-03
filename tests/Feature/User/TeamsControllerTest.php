<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;

class TeamsControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('user.teams'));

        $response->assertOk();
        $response->assertSee('Create Team');
    }

    public function testCreate()
    {
        $response = $this->get(route('user.teams.create'));

        $response->assertOk();
        $response->assertSee('Teams: Create');
        $response->assertSee('Name');
    }

    public function testStore()
    {
        $response = $this->post(route('user.teams.store'), [
            'name' => 'Avengers',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully created a team');
    }

    public function testEdit()
    {
        $team = factory(Team::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->get(route('user.teams.edit', [$team->id]));

        $response->assertOk();
    }

    public function testUpdate()
    {
        $team = factory(Team::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->put(route('user.teams.update', [$team->id]), [
            'name' => 'Avengers'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully updated');
    }

    public function testDelete()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $team = factory(Team::class)->create([
            'user_id' => $this->user->id
        ]);

        $user->teamMemberships()->attach($team);

        $response = $this->delete(route('user.teams.destroy', [$team->id]));

        Notification::assertSentTo($user, InAppNotification::class);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully deleted');
    }
}
