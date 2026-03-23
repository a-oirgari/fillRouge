<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDiagnosticRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seul le médecin propriétaire du RDV peut enregistrer un diagnostic
        $appointment = $this->route('appointment');
        return auth()->check()
            && auth()->user()->isDoctor()
            && $appointment->doctor_id === auth()->user()->doctor->id;
    }

    public function rules(): array
    {
        return [
            'diagnostic'   => ['required', 'string', 'min:10'],
            'prescription' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'diagnostic.required' => 'Le diagnostic est obligatoire.',
            'diagnostic.min'      => 'Le diagnostic doit contenir au moins 10 caractères.',
            'prescription.max'    => 'L\'ordonnance ne peut pas dépasser 2000 caractères.',
        ];
    }
}