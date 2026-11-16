<?php

namespace App\Http\Requests;

use App\Rules\RegistrableEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth('sanctum')->user();

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
                new RegistrableEmail(current: $user->email),
            ],
            'name' => [
                'required',
            ],
        ];
    }
}
