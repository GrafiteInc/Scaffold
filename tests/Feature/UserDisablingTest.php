<?php

namespace Tests\Feature;

use App\Events\UserDisabled;
use App\Events\UserEnabled;
use App\Http\Middleware\EnsureUserIsActive;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserDisablingTest extends TestCase
{
    protected function disabledUser(array $attributes = []): User
    {
        $user = User::factory()->create();

        $user->forceFill(array_merge(['disabled_at' => now()], $attributes))->save();

        return $user->fresh();
    }

    public function test_is_disabled_respects_disabled_until()
    {
        $user = User::factory()->make();
        $this->assertFalse($user->isDisabled());

        $user->forceFill(['disabled_at' => now(), 'disabled_until' => null]);
        $this->assertTrue($user->isDisabled());

        $user->forceFill(['disabled_until' => now()->addHour()]);
        $this->assertTrue($user->isDisabled());

        $user->forceFill(['disabled_until' => now()->subMinute()]);
        $this->assertFalse($user->isDisabled());
    }

    public function test_disabled_and_active_scopes()
    {
        $permanent = $this->disabledUser();
        $timed = $this->disabledUser(['disabled_until' => now()->addDay()]);
        $expired = $this->disabledUser(['disabled_until' => now()->subDay()]);

        $disabled = User::disabled()->pluck('id');
        $active = User::active()->pluck('id');

        $this->assertEqualsCanonicalizing([$permanent->id, $timed->id], $disabled->all());
        $this->assertTrue($active->contains($expired->id));
        $this->assertTrue($active->contains($this->user->id));
        $this->assertFalse($active->contains($permanent->id));
    }

    public function test_disable_revokes_tokens_and_dispatches_event()
    {
        Event::fake([UserDisabled::class, UserEnabled::class]);

        $user = User::factory()->create();
        $user->createToken('device');
        $rememberToken = $user->remember_token;

        $service = app(UserService::class);
        $service->disable($user, 'Spam', now()->addDay(), $this->user);

        $this->assertTrue($user->isDisabled());
        $this->assertSame('Spam', $user->disabled_reason);
        $this->assertSame($this->user->id, $user->disabled_by);
        $this->assertNotSame($rememberToken, $user->remember_token);
        $this->assertCount(0, $user->tokens()->get());
        Event::assertDispatched(UserDisabled::class, fn ($e) => $e->user->is($user) && $e->by->is($this->user));

        $service->enable($user, $this->user);

        $this->assertFalse($user->isDisabled());
        $this->assertNull($user->disabled_reason);
        Event::assertDispatched(UserEnabled::class);
    }

    public function test_disabled_columns_are_hidden_from_serialization()
    {
        $user = $this->disabledUser(['disabled_reason' => 'Spam']);

        $this->assertArrayNotHasKey('disabled_reason', $user->toArray());
        $this->assertArrayNotHasKey('disabled_by', $user->toArray());
    }

    public function test_disabled_web_user_is_logged_out_and_redirected()
    {
        $user = $this->disabledUser();
        $this->be($user);

        $this->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => EnsureUserIsActive::MESSAGE]);

        $this->assertGuest('web');
    }

    public function test_timed_disable_that_has_passed_allows_access()
    {
        $user = $this->disabledUser(['disabled_until' => now()->subMinute()]);
        $this->be($user);

        $this->get(route('dashboard'))->assertOk();
    }

    public function test_disabled_user_is_refused_on_sanctum_api()
    {
        Sanctum::actingAs($this->disabledUser());

        $this->getJson(route('api.users.me'))
            ->assertForbidden()
            ->assertJson(['message' => EnsureUserIsActive::MESSAGE]);
    }

    public function test_disabled_user_is_refused_on_mcp()
    {
        // CI has no committed Passport keys, so sign tokens with a throwaway pair.
        Passport::loadKeysFrom(storage_path('framework/testing/passport'));
        @mkdir(storage_path('framework/testing/passport'), 0777, true);
        Artisan::call('passport:keys', ['--force' => true]);

        Passport::actingAs($this->disabledUser());

        $this->postJson('/mcp/users', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list'], [
            'Accept' => 'application/json, text/event-stream',
        ])->assertForbidden();
    }

    public function test_device_login_rejects_disabled_user()
    {
        $user = $this->disabledUser();

        $this->postJson(route('device.login'), [
            'email' => $user->email,
            'password' => 'secret',
            'device_name' => 'phone',
        ])->assertUnprocessable()->assertJsonPath('errors.email.0', EnsureUserIsActive::MESSAGE);

        $this->assertCount(0, $user->tokens()->get());
    }
}
