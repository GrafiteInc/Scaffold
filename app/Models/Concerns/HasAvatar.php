<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="250" viewBox="0 0 250 250">'
            .'<rect width="100%" height="100%" fill="#eeeeee"/>'
            .'<text x="50%" y="50%" dy=".35em" fill="#464349" font-family="sans-serif" font-size="125" text-anchor="middle">'
            .e($this->avatarInitials())
            .'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * Initials for the generated avatar: first and last word, or the first two letters of a single word.
     */
    protected function avatarInitials(): string
    {
        $words = Str::of((string) $this->name)
            ->replaceMatches('/[^\pL\pN\s-]/u', '')
            ->replace('-', ' ')
            ->squish()
            ->explode(' ')
            ->filter();

        if ($words->count() > 1) {
            return Str::upper(Str::substr($words->first(), 0, 1).Str::substr($words->last(), 0, 1));
        }

        return Str::upper(Str::substr($words->first() ?? '', 0, 2));
    }
}
