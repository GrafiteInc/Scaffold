<?php

namespace App\Mcp\Tools\Users;

use App\Mcp\Tools\Users\Concerns\FormatsUsers;
use App\Services\UserService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
#[Name('get_user')]
#[Description('Admin only. Get a single user by id, e.g. one found with search_users.')]
class GetUserTool extends Tool
{
    use FormatsUsers;

    public function __construct(protected UserService $service) {}

    public function shouldRegister(Request $request): bool
    {
        return $this->isAdmin($request);
    }

    public function handle(Request $request): Response
    {
        if (! $this->isAdmin($request)) {
            return $this->forbidden();
        }

        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $user = $this->service->find((int) $request->get('id'));

        if (! $user) {
            return Response::error('User not found.');
        }

        return Response::json($this->user($user));
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('The user id.')->required(),
        ];
    }
}
