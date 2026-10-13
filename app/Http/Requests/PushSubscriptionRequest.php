<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushSubscriptionRequest extends FormRequest
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
     * The shape matches the browser's PushSubscription.toJSON().
     *
     * @return array
     */
    public function rules()
    {
        return [
            'endpoint' => [
                'required',
                'url:https',
                'max:1024',
            ],
            'keys.p256dh' => [
                'required',
                'string',
            ],
            'keys.auth' => [
                'required',
                'string',
            ],
            'contentEncoding' => [
                'nullable',
                'in:aesgcm,aes128gcm',
            ],
        ];
    }
}
