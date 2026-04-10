@extends('layouts.app')
@section('title', __('patient.dashboard.meta_title'))

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('patient.dashboard.title', ['name' => auth()->user()->name]) }}</h1>
            <p class="text-gray-500">{{ __('patient.dashboard.subtitle') }}</p>
        </div>
        <a href="{{ route('doctors.search') }}"
           class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> {{ __('patient.dashboard.cta_book') }}
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200/90">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-calendar-check text-primary-600"></i>
            {{ __('patient.dashboard.upcoming') }}
        </h2>

        @if($upcomingAppointments->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-calendar-xmark text-4xl mb-3"></i>
                <p>{{ __('patient.dashboard.no_upcoming') }}</p>
                <a href="{{ route('doctors.search') }}" class="text-primary-600 hover:underline text-sm mt-2 inline-block">
                    {{ __('patient.dashboard.find_doctor') }}
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($upcomingAppointments as $apt)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-primary-600"></i>
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
                            {{ __('appointment.status.'.$apt->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.appointments') }}" class="text-primary-600 text-sm hover:underline mt-4 inline-block">
                {{ __('patient.dashboard.see_all_apt') }} →
            </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200/90">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-violet-600"></i>
            {{ __('patient.dashboard.history') }}
        </h2>

        @if($recentHistory->isEmpty())
            <p class="text-gray-400 text-center py-6">{{ __('patient.dashboard.no_history') }}</p>
        @else
            <div class="space-y-3">
                @foreach($recentHistory as $apt)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div>
                        <p class="font-medium text-gray-800">Dr. {{ $apt->doctor->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $apt->date->format('d/m/Y') }}</p>
                        @if($apt->consultation)
                            <p class="text-xs text-gray-600 mt-1 line-clamp-1">
                                <i class="fas fa-stethoscope mr-1 text-violet-500"></i>
                                {{ Str::limit($apt->consultation->diagnostic, 60) }}
                            </p>
                        @endif
                    </div>
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium">{{ __('patient.dashboard.completed') }}</span>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.history') }}" class="text-primary-600 text-sm hover:underline mt-4 inline-block">
                {{ __('patient.dashboard.see_full_history') }} →
            </a>
        @endif
    </div>
</div>
@endsection
