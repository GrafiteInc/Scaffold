<?php

namespace App\Models\Concerns;

use App\Models\Thread;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasThreads
{
    /**
     * @return HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * The one open thread, if any.
     */
    public function activeThread(): ?Thread
    {
        return $this->threads()
            ->open()
            ->latest('last_message_at')
            ->first();
    }
}
