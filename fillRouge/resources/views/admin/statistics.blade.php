@extends('layouts.app')
@section('title', 'Statistiques')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-chart-bar text-green-500 mr-2"></i>Statistiques
    </h1>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-5">Rendez-vous par mois ({{ date('Y') }})</h2>
        @php
            $months = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
            $maxVal  = max(array_merge([1], $stats['appointments_by_month']->toArray()));
        @endphp
        <div class="flex items-end gap-2 h-40">
            @for($i = 1; $i <= 12; $i++)
            @php $count = $stats['appointments_by_month'][$i] ?? 0; @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-500">{{ $count ?: '' }}</span>
                <div class="w-full bg-blue-500 rounded-t-md transition-all"
                     style="height: {{ $maxVal > 0 ? round(($count / $maxVal) * 120) : 4 }}px; min-height: 4px;"></div>
                <span class="text-xs text-gray-400">{{ $months[$i - 1] }}</span>
            </div>
            @endfor
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Top 5 médecins</h2>
            @if($stats['top_doctors']->isEmpty())
                <p class="text-gray-400 text-center py-4">Aucune donnée</p>
            @else
            <div class="space-y-3">
                @foreach($stats['top_doctors'] as $index => $doctor)
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-500' }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Dr. {{ $doctor->user->name }}</p>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                            @php $topCount = $stats['top_doctors']->first()->appointments_count ?: 1; @endphp
                            <div class="bg-blue-500 h-1.5 rounded-full"
                                 style="width: {{ round(($doctor->appointments_count / $topCount) * 100) }}%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">{{ $doctor->appointments_count }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Médecins par spécialité</h2>
            @if($stats['speciality_stats']->isEmpty())
                <p class="text-gray-400 text-center py-4">Aucune donnée</p>
            @else
            <div class="space-y-2">
                @foreach($stats['speciality_stats']->sortByDesc('doctors_count')->take(8) as $spec)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ $spec->name }}</span>
                    <div class="flex items-center gap-2">
                        <div class="w-24 bg-gray-100 rounded-full h-2">
                            @php $maxSpec = $stats['speciality_stats']->max('doctors_count') ?: 1; @endphp
                            <div class="bg-purple-500 h-2 rounded-full"
                                 style="width: {{ round(($spec->doctors_count / $maxSpec) * 100) }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-gray-600 w-4 text-right">{{ $spec->doctors_count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection