@extends('layouts.app')
@section('title', __('nav.dashboard'))

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="border-l-4 border-primary-600 ps-4 rtl:border-l-0 rtl:border-r-4 rtl:pe-4 rtl:ps-0">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('patient.dashboard.title', ['name' => auth()->user()->name]) }}</h1>
            <p class="mt-1 text-slate-600">{{ __('patient.dashboard.subtitle') }}</p>
        </div>
        <a href="{{ route('doctors.search') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-700">
            <i class="fas fa-calendar-plus"></i> {{ __('patient.dashboard.cta_book') }}
        </a>
    </div>

    <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
        <h2 class="mb-5 flex items-center gap-2 border-b border-slate-200/80 pb-3 text-lg font-semibold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                <i class="fas fa-calendar-check"></i>
            </span>
            {{ __('patient.dashboard.upcoming') }}
        </h2>

        @if($upcomingAppointments->isEmpty())
            <div class="rounded-xl bg-slate-50/80 py-10 text-center text-slate-500">
                <i class="fas fa-calendar-xmark mb-3 text-4xl text-slate-300"></i>
                <p>{{ __('patient.dashboard.no_upcoming') }}</p>
                <a href="{{ route('doctors.search') }}" class="mt-3 inline-block text-sm font-medium text-primary-600 hover:text-primary-800">
                    {{ __('patient.dashboard.find_doctor') }}
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($upcomingAppointments as $apt)
                <div class="flex flex-col gap-3 rounded-xl border border-slate-200/80 bg-slate-50/50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Dr. {{ $apt->doctor->user->name }}</p>
                            <p class="text-sm text-slate-600">
                                {{ $apt->doctor->specialities->pluck('name')->join(', ') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-start sm:text-end">
                        <p class="text-sm font-medium text-slate-800">{{ $apt->date->format('d/m/Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $apt->date->format('H:i') }}</p>
                        <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium
                            {{ $apt->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ __('appointment.status.'.$apt->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.appointments') }}" class="mt-4 inline-block text-sm font-medium text-primary-600 hover:text-primary-800">
                {{ __('patient.dashboard.see_all_apt') }} →
            </a>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
        <h2 class="mb-5 flex items-center gap-2 border-b border-slate-200/80 pb-3 text-lg font-semibold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-200/80 text-slate-700">
                <i class="fas fa-clock-rotate-left"></i>
            </span>
            {{ __('patient.dashboard.history') }}
        </h2>

        @if($recentHistory->isEmpty())
            <p class="rounded-xl bg-slate-50/80 py-8 text-center text-slate-500">{{ __('patient.dashboard.no_history') }}</p>
        @else
            <div class="space-y-3">
                @foreach($recentHistory as $apt)
                <div class="flex flex-col gap-2 rounded-xl border border-slate-200/80 bg-slate-50/50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-slate-900">Dr. {{ $apt->doctor->user->name }}</p>
                        <p class="text-sm text-slate-600">{{ $apt->date->format('d/m/Y') }}</p>
                        @if($apt->consultation)
                            <p class="mt-1 line-clamp-1 text-xs text-slate-600">
                                <i class="fas fa-stethoscope me-1 text-slate-500"></i>
                                {{ Str::limit($apt->consultation->diagnostic, 60) }}
                            </p>
                        @endif
                    </div>
                    <span class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">{{ __('patient.dashboard.completed') }}</span>
                </div>
                @endforeach
            </div>
            <a href="{{ route('patient.history') }}" class="mt-4 inline-block text-sm font-medium text-primary-600 hover:text-primary-800">
                {{ __('patient.dashboard.see_full_history') }} →
            </a>
        @endif
    </div>
</div>
@endsection
