<?php

namespace Tests\Feature\Mcp;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\ApiTestCase;

/**
 * Exercises the OAuth 2.1 + PKCE flow an MCP client (Claude) performs against
 * Passport: dynamic client registration, authorization, code exchange, tool call.
 */
class OAuthFlowTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // CI has no committed Passport keys, so sign tokens with a throwaway pair.
        Passport::loadKeysFrom(storage_path('framework/testing/passport'));
        @mkdir(storage_path('framework/testing/passport'), 0777, true);
        Artisan::call('passport:keys', ['--force' => true]);
    }

    public function test_claude_can_register_authorize_and_call_tools()
    {
        // 1. Dynamic client registration (RFC 7591) with Claude's callback.
        $registration = $this->postJson('/oauth/register', [
            'client_name' => 'Claude',
            'redirect_uris' => ['https://claude.ai/api/mcp/auth_callback'],
        ]);

        $registration->assertCreated();
        $clientId = $registration->json('client_id');
        $this->assertNotEmpty($clientId);

        // 2. Authorization request with PKCE, approved by the logged-in user.
        $verifier = Str::random(64);
        $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');

        $authorize = $this->actingAs($this->user)->get('/oauth/authorize?'.http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => 'https://claude.ai/api/mcp/auth_callback',
            'response_type' => 'code',
            'scope' => 'mcp:use',
            'state' => 'xyz',
            'code_challenge' => $challenge,
            'code_challenge_method' => 'S256',
        ]));

        $authorize->assertOk();
        $authorize->assertSee('Authorize Claude');
        $authorize->assertSee($this->user->email);
        // The page must render through the app's Bootstrap guest layout, not the
        // package's Tailwind stub (which references a Vite entry this app lacks).
        $authorize->assertSee('navbar bg-primary', false);
        $authorize->assertDontSee('resources/css/app.css');
        $authToken = $authorize->viewData('authToken');

        $approve = $this->actingAs($this->user)->post('/oauth/authorize', ['auth_token' => $authToken]);
        $approve->assertRedirect();
        parse_str(parse_url($approve->headers->get('Location'), PHP_URL_QUERY), $callback);
        $this->assertSame('xyz', $callback['state']);

        // 3. Exchange the code for tokens.
        $token = $this->postJson('/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'redirect_uri' => 'https://claude.ai/api/mcp/auth_callback',
            'code_verifier' => $verifier,
            'code' => $callback['code'],
        ]);

        $this->assertSame(200, $token->getStatusCode(), (string) $token->getContent());
        $accessToken = $token->json('access_token');
        $this->assertNotEmpty($token->json('refresh_token'));

        // 4. Use the bearer token against the MCP server.
        $call = $this->postJson('/mcp/users', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'get_current_user', 'arguments' => []],
        ], ['Authorization' => "Bearer {$accessToken}", 'Accept' => 'application/json, text/event-stream']);

        $this->assertSame(200, $call->getStatusCode(), (string) $call->getContent());
        $call->assertSee($this->user->email);
    }

    public function test_registration_rejects_unknown_redirect_domains()
    {
        $this->postJson('/oauth/register', [
            'client_name' => 'Evil',
            'redirect_uris' => ['https://evil.example/callback'],
        ])->assertStatus(400)->assertJsonPath('error', 'invalid_redirect_uri');
    }
}
