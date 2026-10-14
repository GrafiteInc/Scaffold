<?php

namespace App\Jobs;

use App\Ai\Agents\ThreadSummarizer;
use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Gives a thread its title and summary. Runs after the first exchange (so
 * the threads list isn't a wall of "Untitled"), when a thread outgrows the
 * agent's context window, and when it closes.
 *
 * A summary is a nicety, never worth failing over: errors are reported and
 * the thread is left as it was.
 */
class SummarizeThread implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;

    public function __construct(public Thread $thread) {}

    public function handle(): void
    {
        $summarizer = new ThreadSummarizer($this->thread);
        $transcript = $summarizer->transcript();

        if (blank($transcript)) {
            return;
        }

        try {
            $response = $summarizer->prompt($transcript);
        } catch (Throwable $exception) {
            report($exception);

            return;
        }

        $this->thread->update([
            'title' => str($response['title'] ?? '')->trim()->limit(120, '')->toString() ?: $this->thread->title,
            'summary' => $response['summary'] ?? $this->thread->summary,
            'meta' => array_merge($this->thread->meta ?? [], [
                'summarized_through' => $this->thread->messages()->max('id'),
            ]),
        ]);
    }
}
