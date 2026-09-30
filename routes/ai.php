<?php

use App\Mcp\Servers\UsersServer;
use Illuminate\Support\Facades\Route;
use Laravel\Mcp\Facades\Mcp;

// OAuth 2.1 discovery + dynamic client registration so MCP clients (Claude Desktop,
// claude.ai connectors) can authorize against Passport without manual client setup.
// Registration is unauthenticated by design (RFC 7591); redirect URIs are limited to
// the domains in config/mcp.php, and the group is throttled.
Route::middleware('throttle:30,1')->group(fn () => Mcp::oauthRoutes());

Mcp::web('/mcp/users', UsersServer::class)
    ->middleware(['auth:api', 'throttle:120,1']);
