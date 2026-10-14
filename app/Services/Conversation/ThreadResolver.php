<?php

namespace App\Services\Conversation;

use App\Ai\Agents\ThreadRelevance;
use App\Jobs\SummarizeThread;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use Illuminate\Support\Str;
use Throwable;

/**
 * Decides which thread an incoming message belongs to.
 *
 * The web chat is explicit: it either continues the thread on screen or
 * asks for a new one. A message that arrives without either (the API with
 * no `thread_id`) is placed automatically. The cheap rules run first and
 * settle almost everything: an explicit reset, a long silence, a rapid
 * reply, a one-word answer. Only a message that arrives after a pause but
 * before the idle cutoff is put to the relevance check, and if that check
 * fails for any reason the message stays in the thread: losing context is
 * a worse mistake than carrying a little extra.
 */
class ThreadResolver
{
    public const EXPLICIT_THREAD = 'explicit_thread';

    public const NEW_THREAD = 'new_thread';

    public const RESET = 'reset';

    public const NO_ACTIVE_THREAD = 'no_active_thread';

    public const IDLE = 'idle';

    public const RAPID_REPLY = 'rapid_reply';

    public const SHORT_REPLY = 'short_reply';

    public const ANSWERING_QUESTION = 'answering_question';

    public const RELATED = 'related';

    public const TOPIC_SHIFT = 'topic_shift';

    public function resolve(User $user, string $text, string $channel, ?Thread $explicit = null, bool $new = false): Resolution
    {
        $text = trim($text);

        if (! is_null($explicit)) {
            return new Resolution($this->resume($user, $explicit), self::EXPLICIT_THREAD, $text);
        }

        $active = $user->activeThread();

        if ($new) {
            $this->close($active, Thread::CLOSED_EXPLICIT);

            return new Resolution($this->open($user, $channel), self::NEW_THREAD, $text);
        }

        $remainder = $this->stripResetWord($text);

        if (! is_null($remainder)) {
            $this->close($active, Thread::CLOSED_EXPLICIT);

            return new Resolution($this->open($user, $channel), self::RESET, $remainder);
        }

        if (is_null($active)) {
            return new Resolution($this->open($user, $channel), self::NO_ACTIVE_THREAD, $text);
        }

        $gap = $this->secondsSince($active);

        if ($gap > config('conversation.idle_minutes') * 60) {
            $this->close($active, Thread::CLOSED_IDLE);

            return new Resolution($this->open($user, $channel), self::IDLE, $text);
        }

        if ($gap <= config('conversation.continuation_seconds')) {
            return new Resolution($active, self::RAPID_REPLY, $text);
        }

        if ($this->isShortReply($text)) {
            return new Resolution($active, self::SHORT_REPLY, $text);
        }

        if ($this->assistantAskedQuestion($active)) {
            return new Resolution($active, self::ANSWERING_QUESTION, $text);
        }

        if (! $this->isRelated($active, $text)) {
            $this->close($active, Thread::CLOSED_TOPIC_SHIFT);

            return new Resolution($this->open($user, $channel), self::TOPIC_SHIFT, $text);
        }

        return new Resolution($active, self::RELATED, $text);
    }

    /**
     * Close the user's open thread; the next message without a thread
     * starts a fresh one.
     */
    public function closeActive(User $user, string $reason = Thread::CLOSED_EXPLICIT): void
    {
        $this->close($user->activeThread(), $reason);
    }

    /**
     * Returns the message with the reset word removed, or null when the
     * message doesn't start with one. The word has to stand alone or be
     * followed by punctuation, so "new: buy milk" resets but "new tires for
     * the car" does not.
     */
    public function stripResetWord(string $text): ?string
    {
        $lower = Str::lower($text);

        foreach (config('conversation.new_thread_words') as $word) {
            if ($lower === $word) {
                return '';
            }

            if (preg_match('/^'.preg_quote($word, '/').'\s*[:\-—,.!]\s*(.*)$/isu', $text, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    protected function isShortReply(string $text): bool
    {
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        return count($words) <= config('conversation.short_reply_words');
    }

    protected function assistantAskedQuestion(Thread $thread): bool
    {
        $last = $thread->messages()->reorder('id', 'desc')->first();

        return ! is_null($last)
            && ! $last->isFromUser()
            && Str::endsWith(rtrim($last->content), '?');
    }

    protected function isRelated(Thread $thread, string $text): bool
    {
        $recent = $thread->recentMessages(config('conversation.relevance_window'));

        if ($recent->isEmpty()) {
            return true;
        }

        try {
            $response = (new ThreadRelevance($thread, $recent))->prompt("New message: {$text}");

            return (bool) ($response['related'] ?? true);
        } catch (Throwable $exception) {
            report($exception);

            return true;
        }
    }

    /**
     * Continuing a thread makes it the active one, reopening it if it had
     * been closed and closing whatever was open before.
     */
    protected function resume(User $user, Thread $thread): Thread
    {
        if ($thread->isOpen()) {
            return $thread;
        }

        $user->threads()
            ->open()
            ->whereKeyNot($thread->getKey())
            ->get()
            ->each(fn (Thread $open) => $this->close($open, Thread::CLOSED_MANUAL));

        $thread->reopen();

        return $thread;
    }

    protected function open(User $user, string $channel): Thread
    {
        return $user->threads()->create([
            'origin_channel' => $channel,
            'status' => Thread::STATUS_OPEN,
            'last_message_at' => now(),
        ]);
    }

    protected function close(?Thread $thread, string $reason): void
    {
        if (is_null($thread) || ! $thread->isOpen()) {
            return;
        }

        $thread->close($reason);

        if ($thread->messages()->where('role', ThreadMessage::ROLE_USER)->exists()) {
            SummarizeThread::dispatch($thread);
        }
    }

    protected function secondsSince(Thread $thread): int
    {
        if (is_null($thread->last_message_at)) {
            return PHP_INT_MAX;
        }

        return (int) abs(now()->diffInSeconds($thread->last_message_at));
    }
}
