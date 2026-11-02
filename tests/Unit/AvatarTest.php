<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    public function test_generated_avatar_is_an_svg_with_initials()
    {
        $user = User::factory()->make(['name' => 'Bruce Wayne', 'avatar' => null]);

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $user->avatar_url);
        $this->assertStringContainsString('>BW</text>', $this->decode($user->avatar_url));
    }

    public function test_single_word_names_use_two_letters()
    {
        $team = (new Team)->forceFill(['name' => 'justice']);

        $this->assertStringContainsString('>JU</text>', $this->decode($team->avatar_url));
    }

    public function test_hyphenated_and_punctuated_names_are_cleaned()
    {
        $user = User::factory()->make(['name' => 'Mary-Jane O\'Brien', 'avatar' => null]);

        $this->assertStringContainsString('>MO</text>', $this->decode($user->avatar_url));
    }

    public function test_uploaded_avatar_is_used_when_present()
    {
        $user = User::factory()->make(['avatar' => 'avatars/bruce.png']);

        $this->assertStringEndsWith('avatars/bruce.png', $user->avatar_url);
    }

    protected function decode(string $url): string
    {
        return base64_decode(substr($url, strlen('data:image/svg+xml;base64,')));
    }
}
