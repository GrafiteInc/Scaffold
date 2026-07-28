<?php

namespace App\Actions;

use App\Models\User;

class AppStats
{
    public static function handle()
    {
        return collect([
            'users' => User::count(),
        ]);
    }
}
