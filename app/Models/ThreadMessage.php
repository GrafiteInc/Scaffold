<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * One turn in a thread. `system` messages are ones the app posted on its
 * own rather than in reply to the user; they're kept so a reply to them
 * has its context.
 */
class ThreadMessage extends Model
{
    use HasFactory;

    public const ROLE_USER = 'user';

    public const ROLE_ASSISTANT = 'assistant';

    public const ROLE_SYSTEM = 'system';

    public const CHANNEL_WEB = 'web';

    public const CHANNEL_API = 'api';

    public const CHANNEL_SYSTEM = 'system';

    public const STATUS_RECEIVED = 'received';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'thread_id',
        'user_id',
        'role',
        'channel',
        'content',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFromUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Font Awesome icon for the channel a message travelled over.
     */
    public static function channelIcon(?string $channel): string
    {
        return match ($channel) {
            self::CHANNEL_API => 'fa-code',
            self::CHANNEL_SYSTEM => 'fa-bell',
            default => 'fa-desktop',
        };
    }

    /**
     * Safe HTML for the chat. Assistant replies are markdown; user messages
     * are shown as typed. Neither is trusted, so raw HTML is escaped.
     */
    public function html(): string
    {
        if ($this->role === self::ROLE_ASSISTANT) {
            return Str::markdown($this->content, [
                'html_input' => 'escape',
                'allow_unsafe_links' => false,
            ]);
        }

        return nl2br(e($this->content));
    }

    /**
     * Tool calls the agent made while producing this reply, for the chips
     * shown under a message in the chat.
     *
     * @return array<int, array{name: string, arguments: array, result: ?string}>
     */
    public function toolCalls(): array
    {
        return $this->meta['tool_calls'] ?? [];
    }
}
