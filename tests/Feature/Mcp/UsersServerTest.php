<?php

namespace Tests\Feature\Mcp;

use App\Mcp\Servers\UsersServer;
use App\Mcp\Tools\Users\GetCurrentUserTool;
use App\Mcp\Tools\Users\GetUserTool;
use App\Mcp\Tools\Users\SearchUsersTool;
use App\Mcp\Tools\Users\UpdateProfileTool;
use App\Models\User;
use Laravel\Mcp\Server\Testing\TestResponse;
use Laravel\Passport\Passport;
use Tests\ApiTestCase;

class UsersServerTest extends ApiTestCase
{
    /**
     * Decode the JSON document a tool returned as its first text content block.
     */
    protected function payload(TestResponse $response): array
    {
        $rpc = (fn () => $this->response->toArray())->call($response);

        return json_decode($rpc['result']['content'][0]['text'], true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_mcp_endpoint_requires_authentication()
    {
        $response = $this->postJson('/mcp/users', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list']);

        $response->assertUnauthorized();
        $response->assertHeader('WWW-Authenticate');
    }

    public function test_oauth_discovery_metadata_is_published()
    {
        $this->getJson('/.well-known/oauth-protected-resource')
            ->assertOk()
            ->assertJsonStructure(['resource', 'authorization_servers']);

        $this->getJson('/.well-known/oauth-authorization-server')
            ->assertOk()
            ->assertJsonPath('authorization_endpoint', route('passport.authorizations.authorize'))
            ->assertJsonPath('token_endpoint', route('passport.token'))
            ->assertJsonPath('registration_endpoint', url('oauth/register'));
    }

    public function test_get_current_user()
    {
        $response = UsersServer::actingAs($this->user)->tool(GetCurrentUserTool::class);

        $response->assertOk();
        $data = $this->payload($response);
        $this->assertSame($this->user->id, $data['id']);
        $this->assertSame($this->user->email, $data['email']);
        $this->assertSame(['admin'], $data['roles']);
        $this->assertArrayNotHasKey('password', $data);
    }

    public function test_update_profile()
    {
        $response = UsersServer::actingAs($this->user)->tool(UpdateProfileTool::class, ['name' => 'Burt Cooper']);

        $response->assertOk();
        $this->assertSame('Burt Cooper', $this->payload($response)['name']);
        $this->assertSame('Burt Cooper', $this->user->fresh()->name);
        $this->assertDatabaseHas('activities', ['user_id' => $this->user->id, 'description' => 'Settings updated.']);
    }

    public function test_update_profile_rejects_taken_email()
    {
        $other = User::factory()->create();

        $response = UsersServer::actingAs($this->user)->tool(UpdateProfileTool::class, ['email' => $other->email]);

        $response->assertHasErrors();
        $this->assertNotSame($other->email, $this->user->fresh()->email);
    }

    public function test_update_profile_requires_a_field()
    {
        $response = UsersServer::actingAs($this->user)->tool(UpdateProfileTool::class, []);

        $response->assertHasErrors();
    }

    public function test_admin_can_search_users()
    {
        $match = User::factory()->create(['name' => 'Zebra Keeper']);
        User::factory()->create(['name' => 'Someone Else']);

        $response = UsersServer::actingAs($this->user)->tool(SearchUsersTool::class, ['query' => 'zebra']);

        $response->assertOk();
        $data = $this->payload($response);
        $this->assertSame(1, $data['total']);
        $this->assertSame($match->id, $data['users'][0]['id']);
    }

    public function test_search_does_not_match_sensitive_columns()
    {
        $hashPrefix = substr($this->user->password, 0, 7);

        $response = UsersServer::actingAs($this->user)->tool(SearchUsersTool::class, ['query' => $hashPrefix]);

        $this->assertSame(0, $this->payload($response)['total']);
    }

    public function test_admin_can_get_user()
    {
        $other = User::factory()->create();

        $response = UsersServer::actingAs($this->user)->tool(GetUserTool::class, ['id' => $other->id]);

        $response->assertOk();
        $this->assertSame($other->email, $this->payload($response)['email']);

        UsersServer::actingAs($this->user)->tool(GetUserTool::class, ['id' => 999999])->assertHasErrors();
    }

    public function test_admin_tools_are_hidden_from_and_refuse_non_admins()
    {
        $member = User::factory()->create();

        UsersServer::actingAs($member)->tool(SearchUsersTool::class, ['query' => 'a'])->assertHasErrors();
        UsersServer::actingAs($member)->tool(GetUserTool::class, ['id' => $this->user->id])->assertHasErrors();

        UsersServer::actingAs($member)->tool(GetCurrentUserTool::class)->assertOk();
    }

    public function test_tools_list_only_shows_admin_tools_to_admins()
    {
        $request = ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list'];
        $headers = ['Accept' => 'application/json, text/event-stream'];

        Passport::actingAs(User::factory()->create());
        $this->postJson('/mcp/users', $request, $headers)
            ->assertOk()
            ->assertSee('get_current_user')
            ->assertDontSee('search_users');

        Passport::actingAs($this->user);
        $this->postJson('/mcp/users', $request, $headers)
            ->assertOk()
            ->assertSee('search_users')
            ->assertSee('get_user');
    }
}
