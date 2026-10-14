<?php

namespace App\Ai\Tools;

use App\Models\Thread;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Lets the agent look back at earlier threads through their summaries, so
 * "what did we decide about the launch last week" works without the user
 * having to reopen that thread.
 *
 * This is the simplest form of retrieval: a keyword match over summaries.
 * A semantic (embeddings / vector store) tool can sit alongside or replace
 * it in `conversation.tools`.
 */
class RecallThreads extends AppTool
{
    public function description(): Stringable|string
    {
        return 'Search summaries of the user\'s earlier conversations with you by keyword. Returns matching conversations with their dates and summaries.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()->description('A distinctive keyword or two.')->required(),
        ];
    }

    protected function run(Request $request): string
    {
        $terms = preg_split('/\s+/', trim((string) $request['search']), -1, PREG_SPLIT_NO_EMPTY);

        if ($terms === []) {
            return 'Give a keyword to search for.';
        }

        $threads = $this->user->threads()
            ->when($this->thread, fn ($query) => $query->whereKeyNot($this->thread->getKey()))
            ->whereNotNull('summary')
            ->where(function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('summary', 'like', "%{$term}%");
                }
            })
            ->latest('last_message_at')
            ->limit(5)
            ->get();

        if ($threads->isEmpty()) {
            return 'No earlier conversations match.';
        }

        return $threads->map(fn (Thread $thread) => sprintf(
            '%s — "%s": %s',
            $thread->last_message_at?->tz($this->timezone())->format('D M j, Y') ?? 'Undated',
            $thread->title ?? 'Untitled',
            $thread->summary
        ))->implode("\n");
    }
}
