<?php

namespace App\Services;

use App\Events\UserDisabled;
use App\Events\UserEnabled;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;

class UserService
{
    /**
     * User model.
     *
     * @var User
     */
    public $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find a user by id.
     */
    public function find(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Search users by name or email.
     *
     * Deliberately narrower than DatabaseSearchable::search(), which LIKE-scans every
     * column (password hashes, 2FA secrets, tokens) and would let API/MCP callers
     * probe those values one character at a time.
     */
    public function search(string $term, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%");
            })
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Update a user's profile.
     *
     * @param  array{name?: string, email?: string}  $payload
     */
    public function update(User $user, array $payload): User
    {
        $user->update(collect($payload)->only(['name', 'email'])->all());

        activity('Settings updated.');

        return $user->refresh();
    }

    /**
     * Disable a user's access, permanently or until a given time.
     *
     * Their API and MCP tokens are revoked and remember-me cookies stop working
     * here. Open web sessions end on their next request (EnsureUserIsActive).
     *
     * @param  User|null  $by  the admin acting, or null when disabled automatically
     */
    public function disable(User $user, ?string $reason = null, ?CarbonInterface $until = null, ?User $by = null): User
    {
        DB::transaction(function () use ($user, $reason, $until, $by) {
            $user->forceFill([
                'disabled_at' => now(),
                'disabled_until' => $until,
                'disabled_reason' => $reason,
                'disabled_by' => $by?->getKey(),
                'remember_token' => Str::random(60),
            ])->save();

            $user->tokens()->delete();

            $this->revokePassportTokens($user);
        });

        activity($by
            ? "Disabled user {$user->email}."
            : "Account {$user->email} disabled automatically.");

        UserDisabled::dispatch($user, $by, $reason, $until);

        return $user->refresh();
    }

    /**
     * Restore a disabled user's access.
     */
    public function enable(User $user, ?User $by = null): User
    {
        $user->forceFill([
            'disabled_at' => null,
            'disabled_until' => null,
            'disabled_reason' => null,
            'disabled_by' => null,
        ])->save();

        activity("Enabled user {$user->email}.");

        UserEnabled::dispatch($user, $by);

        return $user->refresh();
    }

    /**
     * User deliberately lacks Passport's HasApiTokens trait (it conflicts with
     * Sanctum's), so revoke the MCP OAuth tokens by query.
     */
    protected function revokePassportTokens(User $user): void
    {
        $tokenIds = Passport::token()->newQuery()
            ->where('user_id', $user->getKey())
            ->where('revoked', false)
            ->pluck('id');

        if ($tokenIds->isEmpty()) {
            return;
        }

        Passport::token()->newQuery()->whereIn('id', $tokenIds)->update(['revoked' => true]);
        Passport::refreshToken()->newQuery()->whereIn('access_token_id', $tokenIds)->update(['revoked' => true]);
    }
}
