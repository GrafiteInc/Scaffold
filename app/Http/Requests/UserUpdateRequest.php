<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $billingRules = [];

        if (request()->user()->hasActiveSubscription()) {
            $billingRules = [
                'billing_email' => 'required',
                'state' => 'required',
                'country' => 'required',
            ];
        }

        return array_merge([
            'email' => [
                'required',
            ],
            'name' => [
                'required',
            ],
            'avatar' => [
                'image',
            ],
        ], $billingRules);
    }
}
