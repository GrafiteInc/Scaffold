<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Activity;
use App\Services\ActivityService;

class ActivityServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ActivityService::class);
    }

    public function testLog()
    {
        $response = $this->service->log('this is a simple test');

        $this->assertEquals(get_class($response), 'App\Models\Activity');
        $this->assertEquals('this is a simple test', Activity::first()->description);
    }
}
