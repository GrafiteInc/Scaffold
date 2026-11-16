<?php

namespace Tests\Unit\Rules;

use App\Rules\RegistrableEmail;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegistrableEmailTest extends TestCase
{
    protected function passes(string $email, ?RegistrableEmail $rule = null): bool
    {
        return Validator::make(['email' => $email], [
            'email' => [$rule ?? new RegistrableEmail],
        ])->passes();
    }

    public function test_normal_email_passes()
    {
        $this->assertTrue($this->passes('peggy@sterlingcooper.com'));
    }

    public function test_disposable_email_fails()
    {
        $this->assertFalse($this->passes('peggy@10minutemail.com'));
        $this->assertFalse($this->passes('Peggy@10MinuteMail.com'));
    }

    public function test_disposable_whitelist_passes()
    {
        config(['disposable-email.whitelist' => ['10minutemail.com']]);

        $this->assertTrue($this->passes('peggy@10minutemail.com'));
    }

    public function test_allow_list_limits_domains()
    {
        config(['general.registration_allowed_domains' => ['sterlingcooper.com']]);

        $this->assertTrue($this->passes('peggy@sterlingcooper.com'));
        $this->assertTrue($this->passes('peggy@london.sterlingcooper.com'));
        $this->assertFalse($this->passes('peggy@gmail.com'));
        $this->assertFalse($this->passes('peggy@notsterlingcooper.com'));
    }

    public function test_block_list_rejects_domains()
    {
        config(['general.registration_blocked_domains' => ['mccann.com']]);

        $this->assertFalse($this->passes('pete@mccann.com'));
        $this->assertFalse($this->passes('pete@ny.mccann.com'));
        $this->assertTrue($this->passes('pete@sterlingcooper.com'));
    }

    public function test_unchanged_current_email_passes()
    {
        config(['general.registration_allowed_domains' => ['sterlingcooper.com']]);

        $rule = new RegistrableEmail(current: 'Pete@10minutemail.com');

        $this->assertTrue($this->passes('pete@10minutemail.com', $rule));
        $this->assertFalse($this->passes('pete@20minutemail.com', $rule));
    }

    public function test_domain_lists_can_be_skipped()
    {
        config([
            'general.registration_allowed_domains' => ['sterlingcooper.com'],
            'general.registration_blocked_domains' => ['mccann.com'],
        ]);

        $rule = new RegistrableEmail(domainLists: false);

        $this->assertTrue($this->passes('pete@mccann.com', $rule));
        $this->assertTrue($this->passes('pete@gmail.com', $rule));
        $this->assertFalse($this->passes('pete@10minutemail.com', $rule));
    }
}
