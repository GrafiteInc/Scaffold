<?php

namespace Tests\Feature\Controllers\Api;

use App\Ai\Agents\Assistant;
use App\Jobs\SummarizeThread;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Laravel\Sanctum\Sanctum;
use Tests\ApiTestCase;

class ChatControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([SummarizeThread::class]);
    }

    public function test_converse_starts_a_thread()
    {
        Sanctum::actingAs($this->user);
        Assistant::fake(['Hello from the API.']);

        $response = $this->postJson(route('api.chat'), ['message' => 'hello']);

        $response->assertOk();
        $response->assertJsonPath('data.role', 'assistant');
        $response->assertJsonPath('data.content', 'Hello from the API.');
        $response->assertJsonPath('data.channel', 'api');
        $this->assertSame($this->user->activeThread()->id, $response->json('thread.id'));
    }

    public function test_converse_continues_the_given_thread()
    {
        Sanctum::actingAs($this->user);
        Assistant::fake(['Back to it.']);

        $old = Thread::factory()->closed()->create(['user_id' => $this->user->id]);

        $response = $this->postJson(route('api.chat'), ['message' => 'where were we?', 'thread_id' => $old->id]);

        $response->assertOk();
        $response->assertJsonPath('thread.id', $old->id);
        $response->assertJsonPath('thread.status', Thread::STATUS_OPEN);
    }

    public function test_converse_can_start_a_new_thread()
    {
        Sanctum::actingAs($this->user);
        Assistant::fake(['Fresh start.']);

        $active = Thread::factory()->create(['user_id' => $this->user->id]);
        ThreadMessage::factory()->create(['thread_id' => $active->id]);

        $response = $this->postJson(route('api.chat'), ['message' => 'a different topic', 'new' => true]);

        $this->assertNotSame($active->id, $response->json('thread.id'));
    }

    public function test_converse_rejects_someone_elses_thread()
    {
        Sanctum::actingAs($this->user);
        Assistant::fake()->preventStrayPrompts();

        $theirs = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->postJson(route('api.chat'), ['message' => 'hi', 'thread_id' => $theirs->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('thread_id');
    }

    public function test_converse_requires_a_message()
    {
        Sanctum::actingAs($this->user);

        $this->postJson(route('api.chat'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }

    public function test_threads()
    {
        Sanctum::actingAs($this->user);

        Thread::factory()->count(2)->create(['user_id' => $this->user->id]);
        Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->getJson(route('api.chat.threads'));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_requires_authentication()
    {
        $this->postJson(route('api.chat'), ['message' => 'hi'])->assertUnauthorized();
    }

    public function test_disabled()
    {
        Sanctum::actingAs($this->user);
        config(['conversation.enabled' => false]);

        $this->getJson(route('api.chat.threads'))->assertNotFound();
    }
}
