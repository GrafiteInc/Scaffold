<?php

namespace App\Services\Conversation;

use App\Models\Thread;

/**
 * Which thread a message landed in, and why. `text` is the message with any
 * reset word ("new:") taken off the front.
 */
final class Resolution
{
    public function __construct(
        public readonly Thread $thread,
        public readonly string $reason,
        public readonly string $text,
    ) {}

    public function startedNewThread(): bool
    {
        return in_array($this->reason, [
            ThreadResolver::NEW_THREAD,
            ThreadResolver::NO_ACTIVE_THREAD,
            ThreadResolver::RESET,
            ThreadResolver::IDLE,
            ThreadResolver::TOPIC_SHIFT,
        ], true);
    }
}
