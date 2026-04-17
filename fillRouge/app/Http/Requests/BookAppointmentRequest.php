<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        
        return auth()->check() && auth()->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'date'   => ['required', 'date', 'after:now', function ($attribute, $value, $fail) {
                $minutes = date('i', strtotime($value));
                if (!in_array($minutes, ['00', '15', '30', '45'], true)) {
                    $fail('Les rendez-vous se font uniquement par tranche de 15 minutes.');
                }
            }],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La date du rendez-vous est obligatoire.',
            'date.date'     => 'La date n\'est pas valide.',
            'date.after'    => 'La date doit être dans le futur.',
            'reason.max'    => 'Le motif ne peut pas dépasser 500 caractères.',
        ];
    }
}