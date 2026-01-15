<?php

namespace Tests\Feature\Controllers\User;

use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class InvitesControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get(route('user.invites'));

        $response->assertOk();
    }

    public function test_accept()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        $invite = Invite::factory()->create([
            'user_id' => $user->id,
            'email' => $this->user->email,
            'relationship' => 'memberships',
            'model_id' => $team->id,
            'message' => 'new team!',
            'token' => 'foo',
        ]);

        $response = $this->post(route('user.invites.accept', $invite));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Invitation accepted');
    }

    public function test_reject()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        $invite = Invite::factory()->create([
            'user_id' => $user->id,
            'email' => $this->user->email,
            'relationship' => 'memberships',
            'model_id' => $team->id,
            'message' => 'new team!',
            'token' => 'foo',
        ]);

        $response = $this->post(route('user.invites.reject', $invite));

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Invitation rejected');
    }
}
