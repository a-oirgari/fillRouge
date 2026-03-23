<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'city'           => ['nullable', 'string', 'max:100'],
            'bio'            => ['nullable', 'string', 'max:1000'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'specialities'   => ['nullable', 'array'],
            'specialities.*' => ['exists:specialities,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Le nom est obligatoire.',
            'specialities.*.exists'  => 'Une spécialité sélectionnée est invalide.',
        ];
    }
}