@extends('layouts.app')
@section('title', __('doctor.dashboard.meta_title'))

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('doctor.dashboard.title', ['name' => auth()->user()->name]) }}</h1>
            @if(!auth()->user()->doctor->validated)
                <div class="mt-2 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-clock"></i>
                    {{ __('doctor.dashboard.pending_validation') }}
                </div>
            @endif
        </div>
        <a href="{{ route('doctor.profile') }}"
           class="border border-primary-600 text-primary-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-50 transition">
            <i class="fas fa-edit mr-1"></i> {{ __('doctor.dashboard.edit_profile') }}
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('doctor.dashboard.stat_total') }}</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_appointments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-primary-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('doctor.dashboard.stat_pending') }}</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('doctor.dashboard.stat_consultations') }}</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-bell text-yellow-500"></i>
            {{ __('doctor.dashboard.pending_requests', ['count' => $pendingAppointments->count()]) }}
        </h2>

        @if($pendingAppointments->isEmpty())
            <p class="text-gray-400 text-center py-6">{{ __('doctor.dashboard.no_pending') }}</p>
        @else
            <div class="space-y-3">
                @foreach($pendingAppointments as $apt)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <div>
                        <p class="font-medium text-gray-800">{{ $apt->patient->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>{{ $apt->date->format('d/m/Y à H:i') }}
                        </p>
                        @if($apt->reason)
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($apt->reason, 80) }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('doctor.appointments.accept', $apt) }}">
                            @csrf @method('PATCH')
                            <button class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-green-600 transition">
                                <i class="fas fa-check mr-1"></i>{{ __('doctor.dashboard.accept') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('doctor.appointments.refuse', $apt) }}">
                            @csrf @method('PATCH')
                            <button class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 transition">
                                <i class="fas fa-times mr-1"></i>{{ __('doctor.dashboard.refuse') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($todayAppointments->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-day text-primary-600"></i>
            {{ __('doctor.dashboard.today') }}
        </h2>
        <div class="space-y-3">
            @foreach($todayAppointments as $apt)
            <div class="flex items-center justify-between p-4 bg-primary-50 rounded-xl">
                <div>
                    <p class="font-medium text-gray-800">{{ $apt->patient->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $apt->date->format('H:i') }}</p>
                </div>
                <a href="{{ route('doctor.consultation.show', $apt) }}"
                   class="bg-primary-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-primary-700 transition">
                    {{ __('doctor.dashboard.start_consultation') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
