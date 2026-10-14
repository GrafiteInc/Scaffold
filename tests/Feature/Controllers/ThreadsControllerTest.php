<?php

namespace Tests\Feature\Controllers;

use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use Tests\TestCase;

class ThreadsControllerTest extends TestCase
{
    public function test_index()
    {
        Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Offsite planning']);
        Thread::factory()->create(['user_id' => User::factory()->create()->id, 'title' => 'Their chat']);

        $response = $this->get(route('chat'));

        $response->assertOk();
        $response->assertSee('Offsite planning');
        $response->assertDontSee('Their chat');
        $response->assertSee('Start a chat');
    }

    public function test_index_search()
    {
        Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Offsite planning']);
        Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Budget review']);

        $response = $this->get(route('chat', ['search' => 'budget']));

        $response->assertSee('Budget review');
        $response->assertDontSee('Offsite planning');
    }

    public function test_show()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id, 'title' => 'Offsite planning']);
        ThreadMessage::factory()->create(['thread_id' => $thread->id, 'content' => 'Book the venue']);

        $response = $this->get(route('chat.show', $thread->id));

        $response->assertOk();
        $response->assertSee('Book the venue');
    }

    public function test_show_someone_elses_thread()
    {
        $theirs = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->get(route('chat.show', $theirs->id))->assertNotFound();
    }

    public function test_destroy()
    {
        $thread = Thread::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('chat.destroy', $thread->id));

        $response->assertRedirect(route('chat'));
        $this->assertSoftDeleted($thread);
    }

    public function test_destroy_someone_elses_thread()
    {
        $theirs = Thread::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->delete(route('chat.destroy', $theirs->id))->assertNotFound();
        $this->assertNotSoftDeleted($theirs);
    }

    public function test_disabled()
    {
        config(['conversation.enabled' => false]);

        $this->get(route('chat'))->assertNotFound();
    }
}
