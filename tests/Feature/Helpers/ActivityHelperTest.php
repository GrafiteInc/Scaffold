<?php

namespace Tests\Feature\Helpers;

use Tests\TestCase;

class ActivityHelperTest extends TestCase
{
    public function test_activity_log()
    {
        activity('testing stuff');

        $activity = $this->user->activities->last();

        $this->assertEquals('testing stuff', $activity->description);
    }
}
