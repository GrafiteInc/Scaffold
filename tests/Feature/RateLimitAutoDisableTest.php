<?php

namespace Tests\Feature;

use App\Events\RateLimitExceeded;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RateLimitAutoDisableTest extends TestCase
{
    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('throttle:1,1')->get('_test/throttled', fn () => 'ok');

        $this->member = User::factory()->create();

        config([
            'general.auto_disable.enabled' => true,
            'general.auto_disable.strikes' => 3,
            'general.auto_disable.window' => 60,
            'general.auto_disable.duration' => 30,
        ]);
    }

    /**
     * Get throttled once: the first request passes, the next two are rejected
     * inside the same window, which should count as a single strike.
     */
    protected function throttleEpisode(): void
    {
        $this->get('_test/throttled');
        $this->get('_test/throttled')->assertTooManyRequests();
        $this->get('_test/throttled')->assertTooManyRequests();

        $this->travel(61)->seconds();
    }

    public function test_throttled_route_dispatches_event()
    {
        Event::fake([RateLimitExceeded::class]);
        $this->be($this->member);

        $this->get('_test/throttled')->assertOk();
        $this->get('_test/throttled')->assertTooManyRequests();

        Event::assertDispatched(RateLimitExceeded::class, fn ($e) => $e->user->is($this->member)
            && $e->source === RateLimitExceeded::SOURCE_ROUTE
            && $e->retryAfter > 0);
    }

    public function test_repeated_throttling_disables_user_temporarily()
    {
        $this->be($this->member);

        $this->throttleEpisode();
        $this->throttleEpisode();
        $this->assertFalse($this->member->fresh()->isDisabled());

        $this->throttleEpisode();

        $member = $this->member->fresh();
        $this->assertTrue($member->isDisabled());
        $this->assertNull($member->disabled_by);
        // Each episode ends 61s later, so allow for the last one.
        $this->assertTrue($member->disabled_until->between(now()->addMinutes(28), now()->addMinutes(31)));
    }

    public function test_nothing_happens_when_auto_disable_is_off()
    {
        config(['general.auto_disable.enabled' => false]);
        $this->be($this->member);

        foreach (range(1, 4) as $i) {
            $this->throttleEpisode();
        }

        $this->assertFalse($this->member->fresh()->isDisabled());
    }

    public function test_admins_are_exempt()
    {
        foreach (range(1, 4) as $i) {
            $this->throttleEpisode();
        }

        $this->assertFalse($this->user->fresh()->isDisabled());
    }

    public function test_login_lockouts_never_disable_the_account()
    {
        $request = Request::create('/login', 'POST', ['email' => $this->member->email]);

        Event::fake([RateLimitExceeded::class]);
        event(new Lockout($request));
        Event::assertDispatched(RateLimitExceeded::class, fn ($e) => is_null($e->user)
            && $e->source === RateLimitExceeded::SOURCE_LOGIN
            && $e->email === strtolower($this->member->email));
    }

    public function test_login_lockouts_do_not_count_as_strikes()
    {
        foreach (range(1, 5) as $i) {
            event(new Lockout(Request::create('/login', 'POST', ['email' => $this->member->email])));
            $this->travel(61)->seconds();
        }

        $this->assertFalse($this->member->fresh()->isDisabled());
    }
}
