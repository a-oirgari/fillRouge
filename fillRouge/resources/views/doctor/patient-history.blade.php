@extends('layouts.app')
@section('title', 'Historique Patients')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-users text-blue-500 mr-2"></i>Historique patients
    </h1>

    @if($patients->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-user-injured text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune consultation terminée pour l'instant</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($patients as $apt)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between p-5 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $apt->patient->user->name }}</p>
                            <p class="text-xs text-gray-500">
                                Consulté le {{ $apt->date->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('messages.conversation', $apt->patient->user) }}"
                       class="text-blue-600 text-sm hover:underline flex items-center gap-1">
                        <i class="fas fa-comment"></i> Contacter
                    </a>
                </div>

                <div class="p-5 space-y-3">
                    @if($apt->reason)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Motif</p>
                        <p class="text-sm text-gray-700">{{ $apt->reason }}</p>
                    </div>
                    @endif

                    @if($apt->consultation)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Diagnostic posé</p>
                        <p class="text-sm text-gray-700 bg-blue-50 rounded-xl p-3">
                            {{ $apt->consultation->diagnostic }}
                        </p>
                    </div>

                    @if($apt->consultation->prescription)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Ordonnance</p>
                        <p class="text-sm text-gray-700 bg-green-50 rounded-xl p-3 whitespace-pre-line">
                            {{ $apt->consultation->prescription->content }}
                        </p>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $patients->links() }}</div>
    @endif
</div>
@endsection