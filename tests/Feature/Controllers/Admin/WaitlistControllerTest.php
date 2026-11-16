<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Invite;
use App\Models\WaitlistSignup;
use App\Notifications\UserInviteEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WaitlistControllerTest extends TestCase
{
    public function test_index()
    {
        WaitlistSignup::factory()->create(['email' => 'don@sterlingcooper.com']);
        WaitlistSignup::factory()->invited()->create(['email' => 'peggy@sterlingcooper.com']);
        WaitlistSignup::factory()->registered()->create(['email' => 'joan@sterlingcooper.com']);

        $response = $this->get(route('admin.waitlist.index'));

        $response->assertOk();
        $response->assertSee('don@sterlingcooper.com');
        $response->assertSee('peggy@sterlingcooper.com');
        $response->assertSee('Re-invite');
        $response->assertSee('Export CSV');
        $this->assertSame(['pending' => 1, 'invited' => 1, 'registered' => 1], $response->viewData('counts'));
    }

    public function test_search()
    {
        WaitlistSignup::factory()->create(['email' => 'don@sterlingcooper.com']);
        WaitlistSignup::factory()->create(['email' => 'pete@mccann.com']);

        $response = $this->get(route('admin.waitlist.index', ['search' => 'mccann']));

        $response->assertOk();
        $response->assertSee('pete@mccann.com');
        $response->assertDontSee('don@sterlingcooper.com');
    }

    public function test_invite()
    {
        Notification::fake();

        $signup = WaitlistSignup::factory()->create(['email' => 'don@sterlingcooper.com']);

        $response = $this->post(route('admin.waitlist.invite', $signup));

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('message');
        $this->assertNotNull($signup->fresh()->invited_at);

        $invite = Invite::where('email', 'don@sterlingcooper.com')->whereNull('model_id')->first();
        $this->assertNotNull($invite);

        Notification::assertSentOnDemand(
            UserInviteEmail::class,
            fn ($notification) => $notification->token === $invite->token
        );
    }

    public function test_reinvite_resends_the_same_valid_token()
    {
        Notification::fake();

        $signup = WaitlistSignup::factory()->create();

        $this->post(route('admin.waitlist.invite', $signup));
        $this->post(route('admin.waitlist.invite', $signup));

        $invites = Invite::where('email', $signup->email)->get();
        $this->assertCount(1, $invites);

        Notification::assertSentOnDemandTimes(UserInviteEmail::class, 2);
        Notification::assertSentOnDemand(
            UserInviteEmail::class,
            fn ($notification) => $notification->token === $invites->first()->token
        );
    }

    public function test_invite_skips_registered_signups()
    {
        Notification::fake();

        $signup = WaitlistSignup::factory()->registered()->create();

        $this->post(route('admin.waitlist.invite', $signup))->assertSessionHasErrors();

        Notification::assertNothingSent();
    }

    public function test_export()
    {
        WaitlistSignup::factory()->create([
            'email' => 'don@sterlingcooper.com',
            'meta' => ['utm_source' => 'newsletter'],
        ]);

        $response = $this->get(route('admin.waitlist.export'));

        $response->assertOk();
        $this->assertStringStartsWith('text/csv', $response->headers->get('Content-Type'));

        $csv = $response->streamedContent();
        $this->assertStringContainsString('email,name,status', $csv);
        $this->assertStringContainsString('don@sterlingcooper.com', $csv);
        $this->assertStringContainsString('newsletter', $csv);
    }

    public function test_destroy()
    {
        $signup = WaitlistSignup::factory()->create();

        $response = $this->delete(route('admin.waitlist.destroy', $signup));

        $response->assertRedirect(route('admin.waitlist.index'));
        $this->assertModelMissing($signup);
    }
}
