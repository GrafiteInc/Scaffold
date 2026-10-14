<?php

namespace App\Ai\Tools\Users;

use App\Ai\Tools\AppTool;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Example read tool: the user's own profile, in the same shape the REST API
 * and the MCP server return it.
 */
class GetMyProfile extends AppTool
{
    public function description(): Stringable|string
    {
        return 'Get the profile of the user you are talking to: name, email, roles and when they joined.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }

    protected function run(Request $request): string
    {
        return json_encode((new UserResource($this->user))->resolve() + [
            'roles' => $this->user->roles->pluck('name')->all(),
        ]);
    }
}
