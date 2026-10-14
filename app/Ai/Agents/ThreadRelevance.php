<?php

namespace App\Ai\Agents;

use App\Ai\Agents\Concerns\UsesConversationProviders;
use App\Models\Thread;
use App\Models\ThreadMessage;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Collection;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Decides whether a new message belongs to the conversation in progress.
 *
 * Runs on the fast model, only when ThreadResolver's cheap rules (idle gap,
 * rapid reply, short answer) haven't already settled it. It sees the thread
 * summary and the last few turns — never the whole thread.
 */
class ThreadRelevance implements Agent, HasStructuredOutput
{
    use Promptable;
    use UsesConversationProviders;

    /**
     * @param  Collection<int, ThreadMessage>  $recent
     */
    public function __construct(
        protected Thread $thread,
        protected Collection $recent,
    ) {}

    public function provider(): array
    {
        return $this->conversationProviders('fast');
    }

    public function timeout(): int
    {
        return 15;
    }

    public function instructions(): Stringable|string
    {
        $summary = $this->thread->summary ? "Summary of the conversation so far: {$this->thread->summary}\n\n" : '';

        $transcript = $this->recent
            ->map(fn (ThreadMessage $message) => ($message->isFromUser() ? 'User' : 'Assistant').': '.$message->content)
            ->implode("\n");

        return <<<PROMPT
        You route chat messages for an assistant. Decide whether the user's
        NEW message continues the conversation below, or starts an unrelated topic.

        It continues the conversation when it refers back to it (pronouns like "it"
        or "that", corrections, follow-up questions, answers to a question the
        assistant asked) or is about the same subject. It is a new topic when it
        would make complete sense to someone who never saw the conversation and
        has nothing to do with it.

        When in doubt, answer that it is related.

        {$summary}Recent conversation:
        {$transcript}
        PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'related' => $schema->boolean()->required(),
            'reason' => $schema->string()->required(),
        ];
    }
}
