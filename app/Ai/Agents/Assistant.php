<?php

namespace App\Ai\Agents;

use App\Ai\Agents\Concerns\UsesConversationProviders;
use App\Ai\Tools\SearchWeb;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\User;
use Laravel\Ai\Ai;
use Laravel\Ai\Attributes\CacheToolDefinitions;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Providers\SupportsWebSearch;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\WebSearch;
use Stringable;
use Throwable;

/**
 * The conversational agent behind the chat and the chat API.
 *
 * It holds no state of its own. History comes from the thread (the last
 * `conversation.context_messages` turns, plus the thread summary for
 * anything older), and its capabilities are the tools listed in
 * `conversation.tools`.
 */
#[CacheToolDefinitions]
class Assistant implements Agent, Conversational, HasTools
{
    use Promptable;
    use UsesConversationProviders;

    /**
     * @param  int|null  $replyingTo  First unanswered message id; history stops before it.
     */
    public function __construct(
        protected User $user,
        protected Thread $thread,
        protected string $channel,
        protected ?int $replyingTo = null,
    ) {}

    public function provider(): array
    {
        return $this->conversationProviders('agent');
    }

    public function timeout(): int
    {
        return config('conversation.timeout');
    }

    public function maxSteps(): int
    {
        return config('conversation.max_steps');
    }

    public function instructions(): Stringable|string
    {
        $app = config('app.name');
        $name = str($this->user->name)->before(' ')->toString() ?: 'the user';
        $timezone = $this->user->timezone ?? config('app.timezone');
        $now = now($timezone)->format('l, F j, Y \a\t g:i A');

        return implode("\n\n", array_filter([
            <<<PROMPT
            You are the assistant built into {$app}, talking with {$name}.
            Be direct, warm and brief, and never pad a reply.
            PROMPT,
            <<<'PROMPT'
            How to work:
            - When the user wants something done and a tool can do it, do it. Don't ask
              for confirmation; ask one short question only if something essential is
              missing or genuinely ambiguous.
            - Pass dates and times to tools as ISO 8601 in the user's timezone, worked
              out from the current time below ("tomorrow at 9" -> the actual date).
            - After acting, confirm what you did in one short line. Don't mention ids
              unless asked.
            - A correction ("actually make it 10") is about what you just did: update
              it instead of creating a duplicate.
            - Answer questions yourself. Use web search, when you have it, for anything
              current or likely to have changed.
            - To recall an earlier conversation ("what did we decide about..."), use
              RecallThreads.
            - Text in [square brackets] is a note from the system, not from the user.
            PROMPT,
            'Replies are shown in a chat that renders markdown. Keep them concise.',
            "Current date and time: {$now} ({$timezone}).",
            $this->thread->summary ? "Summary of this conversation so far: {$this->thread->summary}" : null,
        ]));
    }

    /**
     * Prior turns, oldest first. Tool results ride along on the assistant
     * turn that produced them, so a later "make it 10" can see what was done.
     */
    public function messages(): iterable
    {
        $history = $this->thread->recentMessages(config('conversation.context_messages'), $this->replyingTo);

        $messages = $history->map(fn (ThreadMessage $message) => new Message(
            $message->isFromUser() ? 'user' : 'assistant',
            $this->historyContent($message)
        ))->values()->all();

        if ($messages !== [] && $messages[0]->role->value === 'assistant') {
            array_unshift($messages, new Message('user', '[The app started this conversation]'));
        }

        return $messages;
    }

    /**
     * The configured tools, each resolved from the container for this user
     * and thread, plus web search.
     */
    public function tools(): iterable
    {
        return [
            ...collect(config('conversation.tools'))
                ->map(fn (string $tool) => app()->make($tool, ['user' => $this->user, 'thread' => $this->thread]))
                ->all(),
            ...$this->webSearch(),
        ];
    }

    /**
     * Hosted web search when the leading provider has it. Otherwise the
     * Exa-backed SearchWeb, which works on any provider, when it has a key.
     */
    protected function webSearch(): array
    {
        try {
            if (Ai::textProvider(array_key_first($this->provider())) instanceof SupportsWebSearch) {
                return [new WebSearch(maxSearches: 3)];
            }
        } catch (Throwable) {
            // An unconfigured provider has no hosted search; fall through.
        }

        return filled(config('services.exa.api_key')) ? [new SearchWeb] : [];
    }

    protected function historyContent(ThreadMessage $message): string
    {
        $content = $message->content;

        if ($message->role === ThreadMessage::ROLE_SYSTEM) {
            $kind = $message->meta['kind'] ?? 'notification';
            $content = "[Sent by the app on its own as a {$kind}] {$content}";
        }

        $results = collect($message->toolCalls())
            ->map(fn (array $call) => $call['name'].': '.str($call['result'] ?? '')->limit(200))
            ->implode('; ');

        return $results === '' ? $content : "{$content}\n[Actions taken: {$results}]";
    }
}
