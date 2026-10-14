<?php

namespace App\Livewire;

use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Services\ConversationService;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * The chat. Given a thread it continues that thread (reopening it if it was
 * closed); without one it is a new chat, and the first message starts a
 * thread and moves the URL to it.
 *
 * Sending is two round trips so the message shows up straight away: send()
 * records it, then respond() runs the agent while a typing indicator shows.
 */
class ThreadChat extends Component
{
    #[Locked]
    public ?int $threadId = null;

    public string $message = '';

    public function mount(?Thread $thread = null): void
    {
        if ($thread?->exists) {
            abort_unless($thread->user_id === auth()->id(), 403);

            $this->threadId = $thread->id;
        }
    }

    public function send(ConversationService $conversation): void
    {
        $this->validate(['message' => 'required|string|max:4000']);

        $thread = $this->thread();

        $message = $conversation->receive(auth()->user(), $this->message, ThreadMessage::CHANNEL_WEB, [
            'thread' => $thread,
            'new' => is_null($thread),
        ]);

        $this->message = '';
        $this->threadId = $message->thread_id;

        $url = route('chat.show', $message->thread_id);

        $this->js("history.replaceState(null, '', '{$url}'); \$wire.respond()");
    }

    public function respond(ConversationService $conversation): void
    {
        if ($thread = $this->thread()) {
            $conversation->respond($thread);
        }
    }

    public function thread(): ?Thread
    {
        if (is_null($this->threadId)) {
            return null;
        }

        return auth()->user()->threads()->find($this->threadId);
    }

    public function render()
    {
        $thread = $this->thread();

        return view('livewire.thread-chat', [
            'thread' => $thread,
            'messages' => $thread ? $thread->recentMessages(200) : collect(),
            'awaitingReply' => $thread && $thread->unansweredMessages()->isNotEmpty(),
            'timezone' => auth()->user()->timezone ?? config('app.timezone'),
        ]);
    }
}
