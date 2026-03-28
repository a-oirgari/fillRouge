@extends('layouts.app')
@section('title', 'Consultation')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('doctor.appointments') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Consultation</h1>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations patient</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Nom</p>
                <p class="font-medium text-gray-800">{{ $appointment->patient->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Date du RDV</p>
                <p class="font-medium text-gray-800">{{ $appointment->date->format('d/m/Y à H:i') }}</p>
            </div>
            @if($appointment->patient->phone)
            <div>
                <p class="text-gray-500">Téléphone</p>
                <p class="font-medium text-gray-800">{{ $appointment->patient->phone }}</p>
            </div>
            @endif
            @if($appointment->reason)
            <div class="col-span-2">
                <p class="text-gray-500">Motif</p>
                <p class="font-medium text-gray-800">{{ $appointment->reason }}</p>
            </div>
            @endif
        </div>
    </div>

    
    @if($appointment->status !== 'completed')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-stethoscope text-blue-500 mr-2"></i>Diagnostic et ordonnance
        </h2>
        <form method="POST" action="{{ route('doctor.consultation.save', $appointment) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Diagnostic *</label>
                <textarea name="diagnostic" rows="5" required
                          placeholder="Saisir le diagnostic médical..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ $appointment->consultation?->diagnostic }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordonnance (optionnel)</label>
                <textarea name="prescription" rows="4"
                          placeholder="Médicaments prescrits, dosage, durée..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ $appointment->consultation?->prescription?->content }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer et terminer
                </button>
                <a href="{{ route('messages.conversation', $appointment->patient->user) }}"
                   class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition">
                    <i class="fas fa-comment mr-2"></i>Envoyer un message
                </a>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Diagnostic enregistré</h2>
        <div class="bg-gray-50 rounded-xl p-4 mb-4">
            <p class="text-sm text-gray-500 mb-1">Diagnostic</p>
            <p class="text-gray-800">{{ $appointment->consultation->diagnostic }}</p>
        </div>
        @if($appointment->consultation->prescription)
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Ordonnance</p>
            <p class="text-gray-800">{{ $appointment->consultation->prescription->content }}</p>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection