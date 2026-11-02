<?php

namespace App\Ai\Agents\Concerns;

/**
 * Builds an agent's provider failover list from `conversation.providers`.
 *
 * The configured default (`AI_DEFAULT_PROVIDER`) goes first, and providers
 * without a key are dropped: an auth error doesn't fail over in Laravel AI,
 * so a keyless provider at the front would sink every call.
 */
trait UsesConversationProviders
{
    /**
     * @param  string  $tier  `agent` or `fast`
     * @return array<string, string>
     */
    protected function conversationProviders(string $tier): array
    {
        $providers = config("conversation.providers.{$tier}");
        $default = config('ai.default');

        // The default provider goes first; the rest keep their configured order.
        if (isset($providers[$default])) {
            $providers = [$default => $providers[$default]] + $providers;
        }

        $usable = array_filter(
            $providers,
            fn ($model, $name) => filled(config("ai.providers.{$name}.key")),
            ARRAY_FILTER_USE_BOTH
        );

        // With no keys at all (tests, fakes) there's nothing to protect against.
        return $usable ?: $providers;
    }
}
