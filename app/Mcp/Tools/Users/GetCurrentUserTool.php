<?php

namespace App\Mcp\Tools\Users;

use App\Mcp\Tools\Users\Concerns\FormatsUsers;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
#[Name('get_current_user')]
#[Description('Get the profile of the authenticated user: id, name, email, avatar, verification status and roles.')]
class GetCurrentUserTool extends Tool
{
    use FormatsUsers;

    public function handle(Request $request): Response
    {
        return Response::json($this->user($request->user()));
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
