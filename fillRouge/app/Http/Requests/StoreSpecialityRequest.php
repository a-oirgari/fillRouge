<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:specialities,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la spécialité est obligatoire.',
            'name.unique'   => 'Cette spécialité existe déjà.',
            'name.max'      => 'Le nom ne peut pas dépasser 100 caractères.',
        ];
    }
}