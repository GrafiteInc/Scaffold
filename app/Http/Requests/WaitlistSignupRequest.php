<?php

namespace App\Http\Requests;

use App\Rules\RegistrableEmail;
use App\Services\WaitlistService;
use Illuminate\Foundation\Http\FormRequest;

class WaitlistSignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * No `unique` rule on purpose: telling people an email is already on the
     * list would let anyone check who has signed up.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $tracked = collect(WaitlistService::TRACKED_PARAMS)
            ->mapWithKeys(fn ($param) => [$param => 'nullable|string|max:255']);

        return [
            'email' => ['required', 'email', 'max:255', new RegistrableEmail],
            'name' => 'nullable|string|max:255',
            'landing_path' => 'nullable|string|max:255',
            ...$tracked->all(),
        ];
    }
}
