<?php

namespace Tests\Unit\Ai;

use App\Ai\Agents\Assistant;
use App\Ai\Tools\AppTool;
use App\Ai\Tools\RecallThreads;
use App\Ai\Tools\SearchWeb;
use App\Ai\Tools\Users\GetMyProfile;
use App\Ai\Tools\Users\UpdateMyProfile;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Tools\Request;
use RuntimeException;
use Tests\TestCase;

class AppToolsTest extends TestCase
{
    public function test_recall_threads_only_searches_the_users_own_earlier_threads()
    {
        $current = Thread::factory()->create(['user_id' => $this->user->id, 'summary' => 'Talked about the launch today.']);
        Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Launch plan', 'summary' => 'Set the launch for Friday.']);
        Thread::factory()->create(['user_id' => User::factory()->create()->id, 'title' => 'Their launch', 'summary' => 'Someone else\'s launch.']);

        $result = (string) (new RecallThreads($this->user, $current))->handle(new Request(['search' => 'launch']));

        $this->assertStringContainsString('Set the launch for Friday.', $result);
        $this->assertStringNotContainsString('today', $result);
        $this->assertStringNotContainsString('Someone else', $result);
    }

    public function test_recall_threads_with_no_match()
    {
        $result = (string) (new RecallThreads($this->user))->handle(new Request(['search' => 'nothing']));

        $this->assertSame('No earlier conversations match.', $result);
    }

    public function test_get_my_profile_returns_the_api_representation()
    {
        $result = json_decode((string) (new GetMyProfile($this->user))->handle(new Request([])), true);

        $this->assertSame($this->user->email, $result['email']);
        $this->assertSame(['admin'], $result['roles']);
    }

    public function test_update_my_profile_goes_through_the_user_service()
    {
        $result = (string) (new UpdateMyProfile($this->user))->handle(new Request(['name' => 'Grace Hopper']));

        $this->assertSame('Name changed to Grace Hopper.', $result);
        $this->assertSame('Grace Hopper', $this->user->refresh()->name);
    }

    public function test_invalid_arguments_are_handed_back_to_the_model()
    {
        $result = (string) (new UpdateMyProfile($this->user))->handle(new Request(['name' => '']));

        $this->assertStringStartsWith('Invalid arguments:', $result);
    }

    public function test_failures_are_handed_back_to_the_model()
    {
        $tool = new class($this->user) extends AppTool
        {
            public function description(): string
            {
                return 'Always fails.';
            }

            public function schema(JsonSchema $schema): array
            {
                return [];
            }

            protected function run(Request $request): string
            {
                throw new RuntimeException('Nope');
            }
        };

        $this->assertSame('That failed: Nope', (string) $tool->handle(new Request([])));
    }

    public function test_dates_are_read_in_the_users_timezone()
    {
        config(['app.timezone' => 'America/Toronto']);

        $tool = new class($this->user) extends RecallThreads
        {
            public function parse(string $value)
            {
                return $this->date($value);
            }
        };

        $this->assertSame('America/Toronto', $tool->parse('2026-10-14T09:00:00')->tzName);
        $this->assertNull($tool->parse(''));
    }

    public function test_the_assistant_resolves_the_configured_tools()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);

        $tools = collect((new Assistant($this->user, $thread, 'web'))->tools());

        $this->assertTrue($tools->contains(fn ($tool) => $tool instanceof RecallThreads));
        $this->assertTrue($tools->contains(fn ($tool) => $tool instanceof GetMyProfile));
        $this->assertTrue($tools->contains(fn ($tool) => $tool instanceof UpdateMyProfile));
    }

    public function test_search_web_formats_exa_results()
    {
        Http::fake(['api.exa.ai/*' => Http::response(['results' => [
            ['title' => 'Laravel AI', 'publishedDate' => '2026-10-01T00:00:00Z', 'url' => 'https://laravel.com', 'highlights' => ['The official AI SDK.']],
        ]])]);

        $result = (string) (new SearchWeb)->handle(new Request(['query' => 'laravel ai']));

        $this->assertStringContainsString('# Laravel AI', $result);
        $this->assertStringContainsString('2026-10-01', $result);
        $this->assertStringContainsString('The official AI SDK.', $result);
    }

    public function test_search_web_when_exa_is_unavailable()
    {
        Http::fake(['api.exa.ai/*' => Http::response(['error' => 'Unauthorized'], 401)]);

        $result = (string) (new SearchWeb)->handle(new Request(['query' => 'laravel ai']));

        $this->assertSame('Web search is unavailable right now.', $result);
    }
}
