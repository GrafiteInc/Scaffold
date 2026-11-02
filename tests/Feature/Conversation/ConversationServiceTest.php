<?php

namespace Tests\Feature\Conversation;

use App\Ai\Agents\Assistant;
use App\Ai\Agents\ThreadSummarizer;
use App\Jobs\SummarizeThread;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Services\ConversationService;
use Illuminate\Support\Facades\Bus;
use Laravel\Ai\Responses\Data\ToolCall;
use RuntimeException;
use Tests\TestCase;

class ConversationServiceTest extends TestCase
{
    protected ConversationService $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([SummarizeThread::class]);

        $this->conversation = $this->app->make(ConversationService::class);
    }

    public function test_a_message_is_answered_by_the_agent()
    {
        Assistant::fake(['Morning! What can I do?'])->preventStrayPrompts();

        $reply = $this->conversation->converse($this->user, 'good morning', ThreadMessage::CHANNEL_WEB);

        $this->assertSame(ThreadMessage::ROLE_ASSISTANT, $reply->role);
        $this->assertSame('Morning! What can I do?', $reply->content);
        $this->assertSame(
            ThreadMessage::STATUS_COMPLETED,
            $reply->thread->messages()->where('role', 'user')->value('status')
        );
        Assistant::assertPrompted('good morning');
    }

    public function test_tool_calls_run_and_are_recorded_on_the_reply()
    {
        Assistant::fake([
            new ToolCall('call_1', 'UpdateMyProfile', ['name' => 'Ada Lovelace']),
            'Done, you are now Ada Lovelace.',
        ])->preventStrayPrompts();

        $reply = $this->conversation->converse($this->user, 'change my name to Ada Lovelace', ThreadMessage::CHANNEL_WEB);

        $this->assertSame('Ada Lovelace', $this->user->refresh()->name);

        $call = $reply->toolCalls()[0];
        $this->assertSame('UpdateMyProfile', $call['name']);
        $this->assertSame('Name changed to Ada Lovelace.', $call['result']);
    }

    public function test_history_carries_earlier_turns_and_their_tool_results()
    {
        Assistant::fake([
            new ToolCall('call_1', 'UpdateMyProfile', ['name' => 'Ada Lovelace']),
            'Done.',
            'Changed it back.',
        ]);

        $first = $this->conversation->converse($this->user, 'change my name to Ada Lovelace', ThreadMessage::CHANNEL_WEB);
        $this->conversation->converse($this->user, 'actually undo that', ThreadMessage::CHANNEL_WEB, ['thread' => $first->thread]);

        $thread = $first->thread;
        $messages = collect((new Assistant($this->user, $thread, 'web', $thread->messages()->max('id') + 1))->messages());

        $this->assertSame(['user', 'assistant', 'user', 'assistant'], $messages->map(fn ($m) => $m->role->value)->all());
        $this->assertStringContainsString('[Actions taken: UpdateMyProfile: Name changed to Ada Lovelace.]', $messages[1]->content);
    }

    public function test_the_thread_summary_is_part_of_the_instructions()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id, 'summary' => 'Planning the spring launch.']);

        $instructions = (string) (new Assistant($this->user, $thread, 'web'))->instructions();

        $this->assertStringContainsString('Planning the spring launch.', $instructions);
        $this->assertStringContainsString(config('app.name'), $instructions);
    }

    public function test_a_failed_agent_call_still_gets_a_reply()
    {
        Assistant::fake(fn () => throw new RuntimeException('Overloaded'));

        $reply = $this->conversation->converse($this->user, 'hello', ThreadMessage::CHANNEL_WEB);

        $this->assertSame(__('conversation.failed'), $reply->content);
        $this->assertTrue($reply->meta['fallback']);
        $this->assertStringContainsString('Overloaded', $reply->meta['error']);
    }

    public function test_a_double_send_gets_one_reply()
    {
        Assistant::fake(['Got both.'])->preventStrayPrompts();

        $first = $this->conversation->receive($this->user, 'add the agenda', ThreadMessage::CHANNEL_WEB);
        $this->conversation->receive($this->user, 'and the notes', ThreadMessage::CHANNEL_WEB, ['thread' => $first->thread]);

        $this->conversation->respond($first->thread);
        $this->assertNull($this->conversation->respond($first->thread));

        Assistant::assertPromptedTimes(1);
        Assistant::assertPrompted("add the agenda\nand the notes");
    }

    public function test_a_bare_reset_is_acknowledged_without_the_agent()
    {
        Assistant::fake()->preventStrayPrompts();

        $reply = $this->conversation->converse($this->user, 'new', ThreadMessage::CHANNEL_API);

        $this->assertSame(__('conversation.reset'), $reply->content);
        Assistant::assertNeverPrompted();
    }

    public function test_the_first_exchange_titles_the_thread()
    {
        Assistant::fake(['Hi.']);

        $this->conversation->converse($this->user, 'hello', ThreadMessage::CHANNEL_WEB);

        Bus::assertDispatched(SummarizeThread::class);
    }

    public function test_start_over_closes_the_active_thread()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);

        $this->conversation->startOver($this->user);

        $this->assertSame(Thread::CLOSED_EXPLICIT, $thread->refresh()->closed_reason);
    }

    public function test_summarizing_titles_and_summarizes_the_thread()
    {
        ThreadSummarizer::fake([['title' => 'Spring launch', 'summary' => 'Planned the spring launch for Friday.']]);

        $thread = Thread::factory()->create(['user_id' => $this->user->id]);
        ThreadMessage::factory()->create(['thread_id' => $thread->id, 'content' => 'Plan the spring launch']);
        $last = ThreadMessage::factory()->fromAssistant()->create(['thread_id' => $thread->id, 'content' => 'Set for Friday.']);

        (new SummarizeThread($thread))->handle();

        $thread->refresh();
        $this->assertSame('Spring launch', $thread->title);
        $this->assertSame('Planned the spring launch for Friday.', $thread->summary);
        $this->assertSame($last->id, $thread->meta['summarized_through']);
        ThreadSummarizer::assertPrompted(fn ($prompt) => str_contains($prompt->prompt, 'User: Plan the spring launch'));
    }

    public function test_a_failed_summary_leaves_the_thread_alone()
    {
        ThreadSummarizer::fake(fn () => throw new RuntimeException('Provider down'));

        $thread = Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Kept']);
        ThreadMessage::factory()->create(['thread_id' => $thread->id]);

        (new SummarizeThread($thread))->handle();

        $this->assertSame('Kept', $thread->refresh()->title);
    }
}
