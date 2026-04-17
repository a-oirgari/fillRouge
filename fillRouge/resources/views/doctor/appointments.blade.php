@extends('layouts.app')
@section('title', 'Mes Rendez-vous')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Rendez-vous</h1>
    </div>

    
    <div class="flex gap-2 flex-wrap">
        @foreach(['all' => 'Tous', 'pending' => 'En attente', 'accepted' => 'Acceptés', 'refused' => 'Refusés', 'completed' => 'Terminés'] as $value => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $value === 'all' ? null : $value]) }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium transition
               {{ (request('status') === $value || ($value === 'all' && !request('status')))
                   ? 'bg-primary-600 text-white'
                   : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    
    @if($appointments->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-calendar text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Aucun rendez-vous trouvé</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($appointments as $apt)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-gray-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $apt->patient->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $apt->patient->user->email }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-calendar mr-1"></i>{{ $apt->date->format('d/m/Y') }}
                                <span class="mx-2">·</span>
                                <i class="fas fa-clock mr-1"></i>{{ $apt->date->format('H:i') }}
                            </p>
                            @if($apt->reason)
                            <p class="text-sm text-gray-500 mt-1 italic">
                                "{{ Str::limit($apt->reason, 100) }}"
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @php
                            $statusConfig = [
                                'pending'   => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-700'],
                                'accepted'  => ['label' => 'Accepté',    'class' => 'bg-green-100 text-green-700'],
                                'refused'   => ['label' => 'Refusé',     'class' => 'bg-red-100 text-red-700'],
                                'completed' => ['label' => 'Terminé',    'class' => 'bg-gray-100 text-gray-700'],
                            ];
                            $config = $statusConfig[$apt->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>

                        <div class="flex gap-2 flex-wrap justify-end">
                            @if($apt->status === 'pending')
                            <form method="POST" action="{{ route('doctor.appointments.accept', $apt) }}">
                                @csrf @method('PATCH')
                                <button class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition">
                                    <i class="fas fa-check mr-1"></i>Accepter
                                </button>
                            </form>
                            <form method="POST" action="{{ route('doctor.appointments.refuse', $apt) }}">
                                @csrf @method('PATCH')
                                <button class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-red-600 transition">
                                    <i class="fas fa-times mr-1"></i>Refuser
                                </button>
                            </form>
                            @endif

                            @if($apt->status === 'accepted')
                            <a href="{{ route('messages.call', $apt->patient->user) }}"
                               class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-blue-700 transition">
                                <i class="fas fa-video mr-1"></i>Lancer l'appel
                            </a>
                            @endif

                            @if($apt->status === 'completed')
                            <a href="{{ route('doctor.consultation.show', $apt) }}"
                               class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition">
                                <i class="fas fa-eye mr-1"></i>Voir diagnostic
                            </a>
                            @endif

                            <a href="{{ route('messages.conversation', $apt->patient->user) }}"
                               class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition">
                                <i class="fas fa-comment mr-1"></i>Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $appointments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection