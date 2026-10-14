<?php

namespace App\Ai\Tools;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Throwable;

/**
 * Base for tools that act on the app for the user the Assistant is talking
 * to. Keep the business logic in a service (the same one the controllers
 * and MCP tools use) and have run() call it.
 *
 * Failures are reported and handed back to the model as text, so it can
 * correct its arguments or tell the user, rather than the whole reply failing.
 */
abstract class AppTool implements Tool
{
    public function __construct(
        protected User $user,
        protected ?Thread $thread = null,
    ) {}

    abstract protected function run(Request $request): string;

    public function handle(Request $request): Stringable|string
    {
        try {
            return $this->run($request);
        } catch (ValidationException $exception) {
            return 'Invalid arguments: '.collect($exception->errors())->flatten()->implode(' ');
        } catch (Throwable $exception) {
            report($exception);

            return 'That failed: '.$exception->getMessage();
        }
    }

    /**
     * Parse a date the model passed, in the user's timezone.
     */
    protected function date(?string $value): ?Carbon
    {
        return blank($value) ? null : Carbon::parse($value, $this->timezone());
    }

    protected function timezone(): string
    {
        return $this->user->timezone ?? config('app.timezone');
    }
}
