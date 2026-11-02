<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class Exa
{
    public static function handle($query = null)
    {
        $response = Http::withHeaders([
            'x-api-key' => config('services.exa.api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.exa.ai/search', [
            'query' => $query,
            'type' => 'auto',
            'numResults' => 10,
            'contents' => [
                'highlights' => [
                    'maxCharacters' => 2000,
                ],
            ],
        ]);

        return $response->json();
    }
}
