@extends('layouts.app')
@section('title', 'Mon Historique Médical')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-history text-purple-500 mr-2"></i>Historique médical
    </h1>

    @if($history->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-file-medical text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucune consultation pour l'instant</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach($history as $apt)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="flex items-center justify-between p-5 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Dr. {{ $apt->doctor->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $apt->date->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">
                        Terminé
                    </span>
                </div>

                
                <div class="p-5 space-y-4">
                    @if($apt->reason)
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Motif de consultation</p>
                        <p class="text-sm text-gray-700">{{ $apt->reason }}</p>
                    </div>
                    @endif

                    @if($apt->consultation)
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">
                            <i class="fas fa-stethoscope mr-1 text-blue-400"></i>Diagnostic
                        </p>
                        <p class="text-sm text-gray-700 bg-blue-50 rounded-xl p-3">
                            {{ $apt->consultation->diagnostic }}
                        </p>
                    </div>

                    @if($apt->consultation->prescription)
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">
                            <i class="fas fa-prescription-bottle mr-1 text-green-400"></i>Ordonnance
                        </p>
                        <p class="text-sm text-gray-700 bg-green-50 rounded-xl p-3 whitespace-pre-line">
                            {{ $apt->consultation->prescription->content }}
                        </p>
                    </div>
                    @endif
                    @else
                    <p class="text-sm text-gray-400 italic">Aucun diagnostic enregistré.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $history->links() }}</div>
    @endif
</div>
@endsection