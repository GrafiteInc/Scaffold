<?php

namespace App\Mcp\Tools\Users;

use App\Mcp\Tools\Users\Concerns\FormatsUsers;
use App\Services\UserService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[IsIdempotent]
#[Name('update_profile')]
#[Description(<<<'TXT'
Update the authenticated user's own profile. Send only the fields to change. Confirm a new
email address with the user before sending it, since it is used to sign in.
TXT)]
class UpdateProfileTool extends Tool
{
    use FormatsUsers;

    public function __construct(protected UserService $service) {}

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
        ]);

        if (empty($data)) {
            return Response::error('Send at least one of name or email.');
        }

        $user = $this->service->update($request->user(), $data);

        return Response::json(['updated' => true] + $this->user($user));
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('New display name.'),
            'email' => $schema->string()->description('New email address; must not belong to another account.'),
        ];
    }
}
