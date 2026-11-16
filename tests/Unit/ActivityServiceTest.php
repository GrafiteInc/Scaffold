<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Services\ActivityService;
use Tests\TestCase;

class ActivityServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ActivityService::class);
    }

    public function test_log()
    {
        $response = $this->service->log('this is a simple test');

        $this->assertEquals(get_class($response), Activity::class);
        $this->assertEquals('this is a simple test', Activity::first()->description);
    }

    public function test_log_masks_secrets()
    {
        request()->merge([
            'email' => 'joe@grafite.ca',
            'password' => 'hunter2-secret',
            'new_password' => 'new-secret',
            'profile' => ['current_password' => 'nested-secret'],
            '_token' => 'csrf-token-value',
        ]);

        $this->service->log('login');

        $payload = Activity::first()->request['payload'];

        $this->assertEquals('joe@grafite.ca', $payload['email']);
        $this->assertEquals(Activity::MASK, $payload['password']);
        $this->assertEquals(Activity::MASK, $payload['new_password']);
        $this->assertEquals(Activity::MASK, $payload['profile']['current_password']);
        $this->assertArrayNotHasKey('_token', $payload);
        $this->assertStringNotContainsString('secret', json_encode(Activity::first()->request));
    }
}
