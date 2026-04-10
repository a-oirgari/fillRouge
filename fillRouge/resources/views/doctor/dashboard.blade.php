@extends('layouts.app')
@section('title', __('nav.dashboard'))

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="max-w-2xl border-l-4 border-primary-600 ps-4 rtl:border-l-0 rtl:border-r-4 rtl:pe-4 rtl:ps-0">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('doctor.dashboard.title', ['name' => auth()->user()->name]) }}</h1>
            @if(!auth()->user()->doctor->validated)
                <div class="mt-3 flex items-start gap-2 rounded-lg border border-amber-200/80 bg-amber-50/90 px-4 py-3 text-sm text-amber-900 shadow-sm">
                    <i class="fas fa-circle-info mt-0.5 text-amber-600"></i>
                    <span>{{ __('doctor.dashboard.pending_validation') }}</span>
                </div>
            @endif
        </div>
        <a href="{{ route('doctor.profile') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-300/80 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
            <i class="fas fa-sliders-h"></i> {{ __('doctor.dashboard.edit_profile') }}
        </a>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-5 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('doctor.dashboard.stat_total') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-slate-900">{{ $stats['total_appointments'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-primary-700">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-5 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('doctor.dashboard.stat_pending') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-amber-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-800">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-5 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('doctor.dashboard.stat_consultations') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums text-emerald-700">{{ $stats['completed'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
        <h2 class="mb-5 flex items-center gap-2 border-b border-slate-200/80 pb-3 text-lg font-semibold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-800">
                <i class="fas fa-inbox"></i>
            </span>
            {{ __('doctor.dashboard.pending_requests', ['count' => $pendingAppointments->count()]) }}
        </h2>

        @if($pendingAppointments->isEmpty())
            <p class="rounded-xl bg-slate-50/80 py-8 text-center text-slate-500">{{ __('doctor.dashboard.no_pending') }}</p>
        @else
            <div class="space-y-3">
                @foreach($pendingAppointments as $apt)
                <div class="flex flex-col gap-4 rounded-xl border border-amber-200/60 bg-amber-50/40 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-slate-900">{{ $apt->patient->user->name }}</p>
                        <p class="text-sm text-slate-600">
                            <i class="fas fa-calendar me-1 text-slate-400"></i>{{ $apt->date->format('d/m/Y à H:i') }}
                        </p>
                        @if($apt->reason)
                        <p class="mt-1 text-xs text-slate-600">{{ Str::limit($apt->reason, 80) }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('doctor.appointments.accept', $apt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                                <i class="fas fa-check me-1"></i>{{ __('doctor.dashboard.accept') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('doctor.appointments.refuse', $apt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                                <i class="fas fa-times me-1"></i>{{ __('doctor.dashboard.refuse') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($todayAppointments->isNotEmpty())
    <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
        <h2 class="mb-5 flex items-center gap-2 border-b border-slate-200/80 pb-3 text-lg font-semibold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-100 text-primary-800">
                <i class="fas fa-sun"></i>
            </span>
            {{ __('doctor.dashboard.today') }}
        </h2>
        <div class="space-y-3">
            @foreach($todayAppointments as $apt)
            <div class="flex flex-col gap-3 rounded-xl border border-primary-100 bg-primary-50/40 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-medium text-slate-900">{{ $apt->patient->user->name }}</p>
                    <p class="text-sm text-slate-600">{{ $apt->date->format('H:i') }}</p>
                </div>
                <a href="{{ route('doctor.consultation.show', $apt) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">
                    {{ __('doctor.dashboard.start_consultation') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
