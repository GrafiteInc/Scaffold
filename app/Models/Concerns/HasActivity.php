<?php

namespace App\Models\Concerns;

use App\Models\Activity;

trait HasActivity
{
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
