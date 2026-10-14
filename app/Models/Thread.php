<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * One conversation with the assistant. A user has at most one open thread
 * (the active one); picking an older thread reopens it, and ThreadResolver
 * decides where a message without a thread belongs.
 */
class Thread extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_OPEN = 'open';

    public const STATUS_CLOSED = 'closed';

    /** Why a thread was closed. */
    public const CLOSED_IDLE = 'idle';

    public const CLOSED_TOPIC_SHIFT = 'topic_shift';

    public const CLOSED_EXPLICIT = 'explicit';

    public const CLOSED_MANUAL = 'manual';

    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'origin_channel',
        'status',
        'closed_reason',
        'last_message_at',
        'closed_at',
        'meta',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'closed_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(ThreadMessage::class)->orderBy('id');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function close(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'closed_reason' => $reason,
            'closed_at' => now(),
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => self::STATUS_OPEN,
            'closed_reason' => null,
            'closed_at' => null,
        ]);
    }

    /**
     * The most recent messages, oldest first, which is the order both the
     * agent and the relevance check read them in.
     *
     * @return Collection<int, ThreadMessage>
     */
    public function recentMessages(int $limit, ?int $beforeId = null): Collection
    {
        return $this->messages()
            ->reorder('id', 'desc')
            ->when($beforeId, fn ($query) => $query->where('id', '<', $beforeId))
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function lastAssistantMessage(): ?ThreadMessage
    {
        return $this->messages()
            ->reorder('id', 'desc')
            ->whereIn('role', [ThreadMessage::ROLE_ASSISTANT, ThreadMessage::ROLE_SYSTEM])
            ->first();
    }

    /**
     * User messages that arrived after the last reply. Usually one; more when
     * someone double-sends before the agent has answered.
     *
     * @return Collection<int, ThreadMessage>
     */
    public function unansweredMessages(): Collection
    {
        $lastReplyId = $this->lastAssistantMessage()?->id ?? 0;

        return $this->messages()
            ->where('role', ThreadMessage::ROLE_USER)
            ->where('id', '>', $lastReplyId)
            ->get();
    }
}
