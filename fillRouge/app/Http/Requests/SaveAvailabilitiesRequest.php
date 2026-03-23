<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAvailabilitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'availabilities'              => ['nullable', 'array'],
            'availabilities.*.day'        => ['required', 'string', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche'],
            'availabilities.*.start_time' => ['required', 'date_format:H:i'],
            'availabilities.*.end_time'   => ['required', 'date_format:H:i', 'after:availabilities.*.start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'availabilities.*.day.required'        => 'Le jour est obligatoire.',
            'availabilities.*.day.in'              => 'Le jour sélectionné est invalide.',
            'availabilities.*.start_time.required' => 'L\'heure de début est obligatoire.',
            'availabilities.*.start_time.date_format' => 'Format d\'heure invalide (HH:MM).',
            'availabilities.*.end_time.required'   => 'L\'heure de fin est obligatoire.',
            'availabilities.*.end_time.after'      => 'L\'heure de fin doit être après l\'heure de début.',
        ];
    }
}