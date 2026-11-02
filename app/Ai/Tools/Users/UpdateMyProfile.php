<?php

namespace App\Ai\Tools\Users;

use App\Ai\Tools\AppTool;
use App\Services\UserService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * Example write tool: validates what the model passed, then hands it to the
 * same UserService the settings page, REST API and MCP server use.
 */
class UpdateMyProfile extends AppTool
{
    public function description(): Stringable|string
    {
        return 'Change the display name of the user you are talking to.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('The new display name.')->required(),
        ];
    }

    protected function run(Request $request): string
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = app(UserService::class)->update($this->user, $data);

        return "Name changed to {$user->name}.";
    }
}
