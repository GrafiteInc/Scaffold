<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Storage;

class UpdateUserAvatar
{
    public static function handle($request)
    {
        if (! is_null($request->avatar)) {
            if (($request->file('avatar')->getSize() / 1024) > 10000) {
                throw new Exception('Avatar file is too big, must be below 10MB.', 1);
            }

            if ($request->user()->avatar) {
                Storage::delete($request->user()->avatar);
            }

            return Storage::putFile('public/avatars', $request->avatar, 'public');
        }

        return null;
    }
}
