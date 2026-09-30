<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Users\GetCurrentUserTool;
use App\Mcp\Tools\Users\GetUserTool;
use App\Mcp\Tools\Users\SearchUsersTool;
use App\Mcp\Tools\Users\UpdateProfileTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Version;

#[Version('1.0.0')]
#[Instructions(<<<'TXT'
Account tools for the authenticated user. Use get_current_user to learn who you are acting
as (name, email, roles) before doing anything else. update_profile changes only the fields
sent. search_users and get_user are admin-only: they are hidden from, and refuse, users
without the admin role.
TXT)]
class UsersServer extends Server
{
    protected array $tools = [
        GetCurrentUserTool::class,
        UpdateProfileTool::class,
        SearchUsersTool::class,
        GetUserTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];

    /**
     * Name the server after the app so each project forked from the scaffold
     * shows up under its own name in Claude's connector list.
     */
    protected function boot(): void
    {
        $this->name = config('app.name').' Users';
    }
}
