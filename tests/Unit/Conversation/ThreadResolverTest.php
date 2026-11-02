<?php

namespace Tests\Unit\Conversation;

use App\Ai\Agents\ThreadRelevance;
use App\Jobs\SummarizeThread;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Services\Conversation\ThreadResolver;
use Illuminate\Support\Facades\Bus;
use RuntimeException;
use Tests\TestCase;

class ThreadResolverTest extends TestCase
{
    protected ThreadResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([SummarizeThread::class]);

        $this->resolver = $this->app->make(ThreadResolver::class);

        config([
            'conversation.idle_minutes' => 45,
            'conversation.continuation_seconds' => 120,
        ]);
    }

    /**
     * An open thread with one exchange, last touched now.
     */
    protected function activeThread(string $userText = 'Help me plan the product launch', string $reply = 'Sure: the launch is set for next Friday.'): Thread
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id, 'last_message_at' => now()]);

        ThreadMessage::factory()->create(['thread_id' => $thread->id, 'content' => $userText]);
        ThreadMessage::factory()->fromAssistant()->create(['thread_id' => $thread->id, 'content' => $reply]);

        return $thread;
    }

    public function test_the_first_message_opens_a_thread()
    {
        $resolution = $this->resolver->resolve($this->user, 'hello there', 'api');

        $this->assertSame(ThreadResolver::NO_ACTIVE_THREAD, $resolution->reason);
        $this->assertTrue($resolution->startedNewThread());
        $this->assertTrue($resolution->thread->isOpen());
        $this->assertSame('api', $resolution->thread->origin_channel);
    }

    public function test_asking_for_a_new_thread_closes_the_active_one()
    {
        ThreadRelevance::fake()->preventStrayPrompts();

        $thread = $this->activeThread();

        $resolution = $this->resolver->resolve($this->user, 'something else entirely', 'web', null, true);

        $this->assertSame(ThreadResolver::NEW_THREAD, $resolution->reason);
        $this->assertTrue($resolution->startedNewThread());
        $this->assertFalse($resolution->thread->is($thread));
        $this->assertSame(Thread::CLOSED_EXPLICIT, $thread->refresh()->closed_reason);
        Bus::assertDispatched(SummarizeThread::class);
    }

    public function test_a_rapid_reply_continues_without_asking_the_model()
    {
        ThreadRelevance::fake()->preventStrayPrompts();

        $thread = $this->activeThread();

        $this->travel(30)->seconds();

        $resolution = $this->resolver->resolve($this->user, 'and can you also draft the announcement email for it', 'api');

        $this->assertSame(ThreadResolver::RAPID_REPLY, $resolution->reason);
        $this->assertTrue($resolution->thread->is($thread));
        ThreadRelevance::assertNeverPrompted();
    }

    public function test_a_long_silence_starts_a_new_thread()
    {
        $thread = $this->activeThread();

        $this->travel(2)->hours();

        $resolution = $this->resolver->resolve($this->user, 'what is on my plate this week', 'api');

        $this->assertSame(ThreadResolver::IDLE, $resolution->reason);
        $this->assertFalse($resolution->thread->is($thread));

        $thread->refresh();
        $this->assertSame(Thread::STATUS_CLOSED, $thread->status);
        $this->assertSame(Thread::CLOSED_IDLE, $thread->closed_reason);
        Bus::assertDispatched(SummarizeThread::class);
    }

    public function test_a_short_reply_continues()
    {
        ThreadRelevance::fake()->preventStrayPrompts();

        $thread = $this->activeThread();

        $this->travel(10)->minutes();

        $resolution = $this->resolver->resolve($this->user, 'make it Monday', 'api');

        $this->assertSame(ThreadResolver::SHORT_REPLY, $resolution->reason);
        $this->assertTrue($resolution->thread->is($thread));
    }

    public function test_an_answer_to_a_question_continues()
    {
        ThreadRelevance::fake()->preventStrayPrompts();

        $thread = $this->activeThread('Book a room for the offsite', 'Which city should it be in?');

        $this->travel(10)->minutes();

        $resolution = $this->resolver->resolve($this->user, 'somewhere near the downtown office', 'api');

        $this->assertSame(ThreadResolver::ANSWERING_QUESTION, $resolution->reason);
        $this->assertTrue($resolution->thread->is($thread));
    }

    public function test_an_unrelated_message_after_a_pause_starts_a_new_thread()
    {
        ThreadRelevance::fake([['related' => false, 'reason' => 'Different subject.']]);

        $thread = $this->activeThread();

        $this->travel(10)->minutes();

        $resolution = $this->resolver->resolve($this->user, 'what is the weather looking like for the weekend', 'api');

        $this->assertSame(ThreadResolver::TOPIC_SHIFT, $resolution->reason);
        $this->assertFalse($resolution->thread->is($thread));
        $this->assertSame(Thread::CLOSED_TOPIC_SHIFT, $thread->refresh()->closed_reason);
        ThreadRelevance::assertPrompted(fn ($prompt) => str_contains($prompt->prompt, 'weather'));
    }

    public function test_a_related_message_after_a_pause_continues()
    {
        ThreadRelevance::fake([['related' => true, 'reason' => 'Same launch.']]);

        $thread = $this->activeThread();

        $this->travel(10)->minutes();

        $resolution = $this->resolver->resolve($this->user, 'actually marketing wants the launch moved to a Tuesday', 'api');

        $this->assertSame(ThreadResolver::RELATED, $resolution->reason);
        $this->assertTrue($resolution->thread->is($thread));
    }

    public function test_a_failed_relevance_check_keeps_the_thread()
    {
        ThreadRelevance::fake(fn () => throw new RuntimeException('Provider down'));

        $thread = $this->activeThread();

        $this->travel(10)->minutes();

        $resolution = $this->resolver->resolve($this->user, 'what is the weather looking like for the weekend', 'api');

        $this->assertSame(ThreadResolver::RELATED, $resolution->reason);
        $this->assertTrue($resolution->thread->is($thread));
        $this->assertTrue($thread->refresh()->isOpen());
    }

    public function test_a_reset_word_alone_starts_fresh()
    {
        $thread = $this->activeThread();

        $resolution = $this->resolver->resolve($this->user, 'New', 'api');

        $this->assertSame(ThreadResolver::RESET, $resolution->reason);
        $this->assertSame('', $resolution->text);
        $this->assertFalse($resolution->thread->is($thread));
        $this->assertSame(Thread::CLOSED_EXPLICIT, $thread->refresh()->closed_reason);
    }

    public function test_a_reset_word_keeps_the_rest_of_the_message()
    {
        $this->activeThread();

        $resolution = $this->resolver->resolve($this->user, 'new: plan the offsite', 'api');

        $this->assertSame(ThreadResolver::RESET, $resolution->reason);
        $this->assertSame('plan the offsite', $resolution->text);
    }

    public function test_a_reset_word_in_ordinary_prose_is_not_a_reset()
    {
        $this->assertNull($this->resolver->stripResetWord('new tires for the car'));
        $this->assertNull($this->resolver->stripResetWord('reset the router password'));
        $this->assertSame('', $this->resolver->stripResetWord('start over'));
        $this->assertSame('plan the trip', $this->resolver->stripResetWord('New thread - plan the trip'));
    }

    public function test_picking_a_closed_thread_reopens_it_and_closes_the_active_one()
    {
        $old = Thread::factory()->closed()->create(['user_id' => $this->user->id]);
        $active = $this->activeThread();

        $resolution = $this->resolver->resolve($this->user, 'picking this back up', 'web', $old);

        $this->assertSame(ThreadResolver::EXPLICIT_THREAD, $resolution->reason);
        $this->assertTrue($resolution->thread->is($old));
        $this->assertTrue($old->refresh()->isOpen());
        $this->assertSame(Thread::CLOSED_MANUAL, $active->refresh()->closed_reason);
    }

    public function test_close_active_closes_the_open_thread()
    {
        $thread = $this->activeThread();

        $this->resolver->closeActive($this->user);

        $this->assertSame(Thread::CLOSED_EXPLICIT, $thread->refresh()->closed_reason);
        $this->assertNull($this->user->activeThread());
    }
}
