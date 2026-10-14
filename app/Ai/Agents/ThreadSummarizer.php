<?php

namespace App\Ai\Agents;

use App\Ai\Agents\Concerns\UsesConversationProviders;
use App\Models\Thread;
use App\Models\ThreadMessage;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Names and summarizes a thread. The title is what the threads list shows;
 * the summary is what the agent and the relevance check carry forward once
 * older turns fall out of the context window.
 */
class ThreadSummarizer implements Agent, HasStructuredOutput
{
    use Promptable;
    use UsesConversationProviders;

    public function __construct(protected Thread $thread) {}

    public function provider(): array
    {
        return $this->conversationProviders('fast');
    }

    public function timeout(): int
    {
        return 30;
    }

    public function instructions(): Stringable|string
    {
        $previous = $this->thread->summary ? "Previous summary (fold it in): {$this->thread->summary}\n\n" : '';

        return <<<PROMPT
        You summarize a conversation between a user and an assistant.

        Return a title of at most six words naming what the conversation is about
        (no quotes, no trailing punctuation), and a summary of two to four sentences
        that records what was asked, what was decided, and anything the assistant
        created or changed (with any dates). Write it so the assistant could pick
        the conversation back up from the summary alone.

        {$previous}
        PROMPT;
    }

    /**
     * The transcript handed over as the prompt.
     */
    public function transcript(): string
    {
        return $this->thread->recentMessages(config('conversation.context_messages') * 2)
            ->map(fn (ThreadMessage $message) => ($message->isFromUser() ? 'User' : 'Assistant').': '.$message->content)
            ->implode("\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->required(),
            'summary' => $schema->string()->required(),
        ];
    }
}
