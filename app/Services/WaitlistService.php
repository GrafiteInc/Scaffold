<?php

namespace App\Services;

use App\Models\User;
use App\Models\WaitlistSignup;
use App\Notifications\WaitlistConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WaitlistService
{
    /**
     * Query params captured with a signup, for attributing where it came from.
     */
    public const TRACKED_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

    public function __construct(
        protected InviteService $invites,
    ) {}

    /**
     * Add someone to the waitlist. Joining twice changes nothing and sends nothing,
     * so the response never reveals whether an email is already on the list.
     */
    public function join(array $data, Request $request): WaitlistSignup
    {
        $email = strtolower(trim($data['email']));

        $meta = array_filter([
            'referrer' => $request->headers->get('referer'),
            'landing_path' => $data['landing_path'] ?? null,
            ...collect($data)->only(self::TRACKED_PARAMS)->all(),
        ]);

        $signup = WaitlistSignup::firstOrCreate(['email' => $email], [
            'name' => $data['name'] ?? null,
            'meta' => $meta ?: null,
        ]);

        if ($signup->wasRecentlyCreated) {
            Notification::route('mail', $email)
                ->notify(new WaitlistConfirmation($signup->name));
        }

        return $signup;
    }

    /**
     * Send the signup an invite to register (works while registration is closed).
     */
    public function invite(WaitlistSignup $signup, User $from): WaitlistSignup
    {
        $this->invites->inviteToApp($signup->email, $from);

        $signup->update(['invited_at' => now()]);

        return $signup;
    }

    /**
     * Mark the signup with this email as registered, if there is one.
     */
    public function markRegistered(string $email): void
    {
        WaitlistSignup::where('email', strtolower(trim($email)))
            ->whereNull('registered_at')
            ->update(['registered_at' => now()]);
    }

    /**
     * Every signup as a CSV download.
     */
    public function export(): StreamedResponse
    {
        $filename = 'waitlist-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['email', 'name', 'status', 'joined_at', 'invited_at', 'registered_at', ...self::TRACKED_PARAMS, 'referrer']);

            WaitlistSignup::orderBy('id')->chunk(500, function ($signups) use ($handle) {
                foreach ($signups as $signup) {
                    $meta = $signup->meta ?? [];

                    fputcsv($handle, [
                        $signup->email,
                        $signup->name,
                        $signup->status,
                        $signup->created_at?->toIso8601String(),
                        $signup->invited_at?->toIso8601String(),
                        $signup->registered_at?->toIso8601String(),
                        ...array_map(fn ($param) => $meta[$param] ?? null, self::TRACKED_PARAMS),
                        $meta['referrer'] ?? null,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
