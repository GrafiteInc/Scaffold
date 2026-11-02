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
#[Name('search_users')]
#[Description('Admin only. Search all users by partial name or email, ordered by name.')]
class SearchUsersTool extends Tool
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
            'query' => ['required', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $users = $this->service->search(
            $request->get('query'),
            (int) $request->get('per_page', 15),
            (int) $request->get('page', 1),
        );

        return Response::json($this->page($users));
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Part of a name or email address.')->required(),
            'page' => $schema->integer()->min(1)->default(1),
            'per_page' => $schema->integer()->min(1)->max(50)->default(15),
        ];
    }
}
