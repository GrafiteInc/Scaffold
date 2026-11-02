<?php

namespace App\Mcp\Tools\Users\Concerns;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;

trait FormatsUsers
{
    /**
     * The REST API's representation of a user, plus role names.
     *
     * @return array<string, mixed>
     */
    protected function user(User $user): array
    {
        return (new UserResource($user))->resolve() + [
            'roles' => $user->roles->pluck('name')->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function page(LengthAwarePaginator $users): array
    {
        return [
            'total' => $users->total(),
            'page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'users' => collect($users->items())->map(fn (User $user) => $this->user($user))->all(),
        ];
    }

    protected function isAdmin(Request $request): bool
    {
        return (bool) $request->user()?->hasRole('admin');
    }

    protected function forbidden(): Response
    {
        return Response::error('This tool is only available to administrators.');
    }
}
