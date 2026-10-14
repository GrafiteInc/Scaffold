<?php

namespace App\Ai\Tools;

use App\Actions\Exa;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Throwable;

/**
 * Web search as an ordinary function tool, backed by Exa. The Assistant uses
 * it in place of the hosted WebSearch when the leading provider has none,
 * so search still works there.
 */
class SearchWeb implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search the web for anything current or likely to have changed. Returns the top results with their title, date, link and the relevant passages.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('A focused search query, e.g. "Toronto Raptors next game".')->required(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        try {
            $response = Exa::handle((string) $request['query']);
        } catch (Throwable $exception) {
            report($exception);

            return 'Web search is unavailable right now.';
        }

        if (! isset($response['results'])) {
            return 'Web search is unavailable right now.';
        }

        if ($response['results'] === []) {
            return 'No results.';
        }

        return collect($response['results'])->take(5)->map(fn (array $result) => implode("\n", array_filter([
            '# '.($result['title'] ?? 'Untitled'),
            isset($result['publishedDate']) ? Str::before($result['publishedDate'], 'T') : null,
            $result['url'] ?? null,
            Str::limit(implode(' … ', $result['highlights'] ?? []), 1000),
        ])))->implode("\n\n");
    }
}
