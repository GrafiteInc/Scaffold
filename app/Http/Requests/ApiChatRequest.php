<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiChatRequest extends FormRequest
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
        return [
            'message' => [
                'required',
                'string',
                'max:4000',
            ],
            // Continue this thread. Without it, the message is placed in the active thread or a new one.
            'thread_id' => [
                'nullable',
                'integer',
                Rule::exists('threads', 'id')
                    ->where('user_id', auth('sanctum')->id())
                    ->whereNull('deleted_at'),
            ],
            // Start a new thread rather than continuing the active one.
            'new' => [
                'boolean',
            ],
        ];
    }
}
