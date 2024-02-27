<?php

namespace Tests\Feature\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamsControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('teams'));

        $response->assertOk();
    }

    public function testIndexWithSubcription()
    {
        $this->withSubscription();

        $response = $this->get(route('teams'));

        $response->assertOk();
        $response->assertSee('Create Team');
    }

    public function testCreate()
    {
        $this->withSubscription();

        $response = $this->get(route('teams.create'));

        $response->assertOk();
        $response->assertSee('Create a Team');
        $response->assertSee('Name');
    }

    public function testStore()
    {
        $this->withSubscription();

        $response = $this->post(route('teams.store'), [
            'name' => 'Avengers',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully created a team');
    }

    public function testEdit()
    {
        $this->withSubscription();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('teams.edit', [$team->id]));

        $response->assertOk();
    }

    public function testUpdate()
    {
        $this->withSubscription();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->put(route('teams.update', [$team->id]), [
            'name' => 'Avengers',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully updated');
    }

    public function testDelete()
    {
        $this->withSubscription();

        Notification::fake();

        $user = User::factory()->create();

        $team = Team::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $user->memberships()->attach($team);

        $response = $this->delete(route('teams.destroy', [$team->id]));

        Notification::assertSentTo($user, InAppNotification::class);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Successfully deleted');
    }
}
