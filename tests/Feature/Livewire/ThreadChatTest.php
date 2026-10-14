<?php

namespace Tests\Feature\Livewire;

use App\Ai\Agents\Assistant;
use App\Jobs\SummarizeThread;
use App\Livewire\ThreadChat;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Tests\TestCase;

class ThreadChatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([SummarizeThread::class]);
    }

    public function test_sending_shows_the_message_then_the_reply()
    {
        Assistant::fake(['Your email is on file.']);

        $component = Livewire::test(ThreadChat::class)
            ->assertSee('Ask anything, or tell me what to do.')
            ->set('message', 'what is my email?')
            ->call('send')
            ->assertSet('message', '')
            ->assertSee('what is my email?')
            ->assertSeeHtml('thread-chat-typing');

        $thread = $this->user->activeThread();
        $component->assertSet('threadId', $thread->id);

        $component->call('respond')
            ->assertSee('Your email is on file.')
            ->assertDontSeeHtml('thread-chat-typing');
    }

    public function test_a_new_chat_starts_a_new_thread_even_with_one_active()
    {
        Assistant::fake(['Hi.']);

        $active = Thread::factory()->create(['user_id' => $this->user->id]);
        ThreadMessage::factory()->create(['thread_id' => $active->id]);

        $component = Livewire::test(ThreadChat::class)
            ->set('message', 'something new')
            ->call('send');

        $this->assertNotSame($active->id, $component->get('threadId'));
        $this->assertSame(Thread::CLOSED_EXPLICIT, $active->refresh()->closed_reason);
    }

    public function test_a_closed_thread_is_reopened_by_continuing_it()
    {
        Assistant::fake(['Picking it back up.']);

        $old = Thread::factory()->closed()->create(['user_id' => $this->user->id, 'title' => 'Offsite planning']);

        Livewire::test(ThreadChat::class, ['thread' => $old])
            ->assertSee('Offsite planning')
            ->assertSee('Continue this chat...')
            ->set('message', 'what did we decide?')
            ->call('send')
            ->call('respond');

        $this->assertTrue($old->refresh()->isOpen());
        $this->assertSame(['user', 'assistant'], $old->messages->pluck('role')->all());
    }

    public function test_assistant_markdown_is_rendered_and_html_is_escaped()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);
        ThreadMessage::factory()->fromAssistant()->create(['thread_id' => $thread->id, 'content' => "**Bold** <script>alert('x')</script>"]);
        ThreadMessage::factory()->create(['thread_id' => $thread->id, 'content' => '<img src=x onerror=alert(1)>']);

        Livewire::test(ThreadChat::class, ['thread' => $thread])
            ->assertSeeHtml('<strong>Bold</strong>')
            ->assertDontSeeHtml('<script>alert')
            ->assertDontSeeHtml('<img src=x');
    }

    public function test_tool_calls_show_as_chips()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);
        ThreadMessage::factory()->fromAssistant()->create([
            'thread_id' => $thread->id,
            'meta' => ['tool_calls' => [['name' => 'GetMyProfile', 'arguments' => [], 'result' => '{}']]],
        ]);

        Livewire::test(ThreadChat::class, ['thread' => $thread])->assertSee('Get My Profile');
    }

    public function test_someone_elses_thread_cannot_be_opened()
    {
        $theirs = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        Livewire::test(ThreadChat::class, ['thread' => $theirs])->assertForbidden();
    }

    public function test_the_thread_id_cannot_be_changed_from_the_browser()
    {
        $theirs = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test(ThreadChat::class)->set('threadId', $theirs->id);
    }

    public function test_an_empty_message_is_rejected()
    {
        Livewire::test(ThreadChat::class)
            ->set('message', '')
            ->call('send')
            ->assertHasErrors(['message' => 'required']);
    }
}
