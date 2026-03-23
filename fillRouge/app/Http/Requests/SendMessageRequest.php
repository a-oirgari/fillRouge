<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:1', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Le message ne peut pas être vide.',
            'content.max'      => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}