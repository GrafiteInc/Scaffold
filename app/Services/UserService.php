<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
