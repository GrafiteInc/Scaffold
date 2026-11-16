<?php

namespace App\Rules;

use Closure;
use EragLaravelDisposableEmail\Rules\DisposableEmailRule;
use EragLaravelDisposableEmail\Support\Domain;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Whether an email address may be used to sign up or be set as a user's own email.
 *
 * Applies the registration allow / block lists (config/general.php) and then the
 * disposable email check (config/disposable-email.php). Use it on every path where
 * a user chooses their own email; admin edits are deliberately unrestricted.
 */
class RegistrableEmail implements ValidationRule
{
    /**
     * @param  string|null  $current  The user's existing email. It always passes, so a
     *                                user whose address is now disallowed can still save
     *                                other settings.
     * @param  bool  $domainLists  Apply the allow / block lists. Invite registration turns
     *                             this off, since the invite is the authorization.
     */
    public function __construct(
        protected ?string $current = null,
        protected bool $domainLists = true,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower(trim((string) $value));

        if ($this->current !== null && $email === strtolower(trim($this->current))) {
            return;
        }

        if ($this->domainLists) {
            $domain = Domain::extract($email);
            $allowed = config('general.registration_allowed_domains', []);

            if (! empty($allowed) && ! $this->matches($domain, $allowed)) {
                $fail(__('Registration is limited to approved email domains.'));

                return;
            }

            if ($this->matches($domain, config('general.registration_blocked_domains', []))) {
                $fail(__('The :attribute belongs to an unauthorized email provider.'));

                return;
            }
        }

        (new DisposableEmailRule)->validate($attribute, $value, $fail);
    }

    /**
     * The domain is one of the given domains, or a subdomain of one.
     *
     * @param  array<int, string>  $domains
     */
    protected function matches(string $domain, array $domains): bool
    {
        foreach ($domains as $candidate) {
            if ($domain === $candidate || str_ends_with($domain, '.'.$candidate)) {
                return true;
            }
        }

        return false;
    }
}
