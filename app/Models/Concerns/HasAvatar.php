<?php

namespace App\Models\Concerns;

use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;

trait HasAvatar
{
    /**
     * Avatar Image Url.
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if (! is_null($this->avatar)) {
            return url(Storage::url($this->avatar));
        }

        return app(InitialAvatar::class)
            ->name($this->name)
            ->height(250)
            ->width(250)
            ->background('#eeeeee')
            ->color('#464349')
            ->generate()
            ->encode('data-url');
    }
}
