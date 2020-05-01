<?php

use App\Services\ActivityService;

/*
 * --------------------------------------------------------------------------
 * Helpers for Activities
 * --------------------------------------------------------------------------
*/

if (! function_exists('activity')) {
    function activity($description)
    {
        return app(ActivityService::class)->log($description);
    }
}
