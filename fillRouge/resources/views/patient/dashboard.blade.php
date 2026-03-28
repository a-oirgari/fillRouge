@extends('layouts.app')
@section('title', 'Mon Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bonjour, {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-500">Voici un aperçu de votre espace santé</p>
        </div>
        <a href="{{ route('doctors.search') }}"
           class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Prendre un rendez-vous
        </a>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-check text-blue-500"></i>
            Prochains rendez-vous
        </h2>

        @if($upcomingAppointments->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-calendar-xmark text-4xl mb-3"></i>
                <p>Aucun rendez-vous à venir</p>
                <a href="{{ route('doctors.search') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                    Chercher un médecin
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($upcomingAppointments as $apt)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Dr. {{ $apt->doctor->user->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $apt->doctor->specialities->pluck('name')->join(', ') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700">{{ $apt->date->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $apt->date->format('H:i') }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $apt->status === 'accepted' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($apt->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.appointments') }}" class="text-blue-600 text-sm hover:underline mt-4 inline-block">
                Voir tous les rendez-vous →
            </a>
        @endif
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-purple-500"></i>
            Historique récent
        </h2>

        @if($recentHistory->isEmpty())
            <p class="text-gray-400 text-center py-6">Aucune consultation pour l'instant</p>
        @else
            <div class="space-y-3">
                @foreach($recentHistory as $apt)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="font-medium text-gray-800">Dr. {{ $apt->doctor->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $apt->date->format('d/m/Y') }}</p>
                        @if($apt->consultation)
                            <p class="text-xs text-gray-600 mt-1 line-clamp-1">
                                <i class="fas fa-stethoscope mr-1 text-purple-500"></i>
                                {{ Str::limit($apt->consultation->diagnostic, 60) }}
                            </p>
                        @endif
                    </div>
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium">Terminé</span>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.history') }}" class="text-blue-600 text-sm hover:underline mt-4 inline-block">
                Voir l'historique complet →
            </a>
        @endif
    </div>
</div>
@endsection