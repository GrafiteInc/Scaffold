<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEnabled
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public ?User $by = null,
    ) {}
}
