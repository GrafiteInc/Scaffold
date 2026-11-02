<?php

namespace App\Services;

use App\Ai\Agents\Assistant;
use App\Jobs\SummarizeThread;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use App\Services\Conversation\ThreadResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\Data\ToolResult;
use Throwable;

/**
 * The conversation layer: every chat message goes in here, and every reply
 * comes out of here.
 *
 * receive() files an inbound message into the right thread; respond() has
 * the Assistant answer whatever is waiting in a thread. converse() does
 * both, for callers waiting on the reply.
 */
class ConversationService
{
    public function __construct(
        protected ThreadResolver $resolver,
    ) {}

    public function enabled(): bool
    {
        return (bool) config('conversation.enabled');
    }

    /**
     * Start over: the next message without a thread opens a new one.
     */
    public function startOver(User $user): void
    {
        $this->resolver->closeActive($user);
    }

    /**
     * Receive a message and answer it now.
     *
     * @param  array{thread?: ?Thread, new?: bool, meta?: array}  $options
     */
    public function converse(User $user, string $text, string $channel, array $options = []): ?ThreadMessage
    {
        $message = $this->receive($user, $text, $channel, $options);

        return $this->respond($message->thread);
    }

    /**
     * Record an inbound message in the thread it belongs to: the one given,
     * a new one when `new` is set, or wherever ThreadResolver places it.
     *
     * @param  array{thread?: ?Thread, new?: bool, meta?: array}  $options
     */
    public function receive(User $user, string $text, string $channel, array $options = []): ThreadMessage
    {
        $resolution = $this->resolver->resolve($user, $text, $channel, $options['thread'] ?? null, $options['new'] ?? false);

        $meta = array_filter([
            'resolution' => $resolution->reason,
            // What the agent is asked, when it differs from what was typed ("new: buy milk").
            'prompt' => $resolution->text !== trim($text) ? $resolution->text : null,
            'reset' => $resolution->reason === ThreadResolver::RESET ?: null,
        ], fn ($value) => ! is_null($value)) + ($options['meta'] ?? []);

        return $this->record($resolution->thread, [
            'role' => ThreadMessage::ROLE_USER,
            'channel' => $channel,
            'content' => trim($text),
            'status' => ThreadMessage::STATUS_RECEIVED,
            'meta' => $meta,
        ]);
    }

    /**
     * Answer everything waiting in the thread with one reply. Returns null
     * when there's nothing waiting.
     */
    public function respond(Thread $thread): ?ThreadMessage
    {
        $pending = $thread->unansweredMessages();

        if ($pending->isEmpty()) {
            return null;
        }

        $user = $thread->user;
        $channel = $pending->last()->channel;
        $prompt = $pending->map(fn (ThreadMessage $message) => $message->meta['prompt'] ?? $message->content)
            ->filter(fn ($text) => filled($text))
            ->implode("\n");

        [$reply, $meta] = blank($prompt)
            ? [__('conversation.reset'), []]
            : $this->ask($user, $thread, $channel, $pending, $prompt);

        $pending->each(fn (ThreadMessage $message) => $message->update(['status' => ThreadMessage::STATUS_COMPLETED]));

        $message = $this->record($thread, [
            'role' => ThreadMessage::ROLE_ASSISTANT,
            'channel' => $channel,
            'content' => $reply,
            'status' => ThreadMessage::STATUS_COMPLETED,
            'meta' => $meta,
        ]);

        $this->summarizeIfDue($thread);

        return $message;
    }

    /**
     * Run the Assistant. If it fails, the user gets an apology rather than
     * an error, and the failure is kept on the reply's meta.
     *
     * @param  Collection<int, ThreadMessage>  $pending
     * @return array{0: string, 1: array}
     */
    protected function ask(User $user, Thread $thread, string $channel, Collection $pending, string $prompt): array
    {
        try {
            $response = (new Assistant($user, $thread, $channel, $pending->first()->id))->prompt($prompt);

            return [
                trim($response->text) ?: 'Done.',
                $this->responseMeta($response),
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                __('conversation.failed'),
                ['fallback' => true, 'error' => Str::limit(class_basename($exception).': '.$exception->getMessage(), 300)],
            ];
        }
    }

    protected function responseMeta(AgentResponse $response): array
    {
        return array_filter([
            'tool_calls' => $response->toolResults
                ->map(fn (ToolResult $result) => [
                    'name' => $result->name,
                    'arguments' => $result->arguments,
                    'result' => Str::limit(is_string($result->result) ? $result->result : json_encode($result->result), 500),
                ])
                ->values()
                ->all(),
            'provider' => $response->meta->provider,
            'model' => $response->meta->model,
            'usage' => $response->usage->toArray(),
        ]);
    }

    protected function record(Thread $thread, array $attributes): ThreadMessage
    {
        $message = $thread->messages()->create($attributes + ['user_id' => $thread->user_id]);

        $thread->update(['last_message_at' => now()]);

        return $message;
    }

    /**
     * Title a thread after its first exchange, and refresh the summary each
     * time another context window's worth of messages has passed.
     */
    protected function summarizeIfDue(Thread $thread): void
    {
        $thread->refresh();

        $firstExchange = is_null($thread->title)
            && $thread->messages()->where('role', ThreadMessage::ROLE_ASSISTANT)->count() === 1;

        $through = $thread->meta['summarized_through'] ?? 0;
        $outgrown = $thread->messages()->where('id', '>', $through)->count() >= config('conversation.context_messages');

        if ($firstExchange || $outgrown) {
            SummarizeThread::dispatch($thread);
        }
    }
}
