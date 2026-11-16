<?php

namespace App\Events;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDisabled
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  User|null  $by  null when disabled automatically
     * @param  CarbonInterface|null  $until  null when disabled permanently
     */
    public function __construct(
        public User $user,
        public ?User $by = null,
        public ?string $reason = null,
        public ?CarbonInterface $until = null,
    ) {}
}
