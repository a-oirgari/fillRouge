@extends('layouts.app')
@section('title', __('app.doctor_dashboard.title'))

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="space-y-2">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
                {{ __('app.doctor_dashboard.greeting', ['name' => auth()->user()->name]) }}
            </h1>
            @if(!auth()->user()->doctor->validated)
                <div class="flex items-start gap-2 rounded-xl border border-amber-200/80 bg-amber-50 px-4 py-3 text-sm text-amber-900 shadow-sm">
                    <i class="fas fa-hourglass-half mt-0.5 text-amber-600"></i>
                    <span>{{ __('app.doctor_dashboard.pending_validation') }}</span>
                </div>
            @endif
        </div>
        <a href="{{ route('doctor.profile') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-primary-200 bg-white px-4 py-2.5 text-sm font-semibold text-primary-800 shadow-sm transition hover:border-primary-300 hover:bg-primary-50">
            <i class="fas fa-user-pen"></i> {{ __('app.doctor_dashboard.edit_profile') }}
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-primary-50 to-white p-5 shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('app.doctor_dashboard.stat_total') }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['total_appointments'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-700">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-amber-200/60 bg-gradient-to-br from-amber-50 to-white p-5 shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('app.doctor_dashboard.stat_pending') }}</p>
                    <p class="mt-1 text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-emerald-200/60 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('app.doctor_dashboard.stat_consultations') }}</p>
                    <p class="mt-1 text-3xl font-bold text-emerald-700">{{ $stats['completed'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-circle-check text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50/90 shadow-md">
        <div class="border-b border-slate-100 bg-amber-500/5 px-6 py-4">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <i class="fas fa-bell"></i>
                </span>
                {{ __('app.doctor_dashboard.pending_requests', ['count' => $pendingAppointments->count()]) }}
            </h2>
        </div>
        <div class="p-6">
            @if($pendingAppointments->isEmpty())
                <p class="py-8 text-center text-slate-500">{{ __('app.doctor_dashboard.no_pending') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($pendingAppointments as $apt)
                    <div class="flex flex-col gap-4 rounded-xl border border-amber-100/80 bg-amber-50/50 p-4 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $apt->patient->user->name }}</p>
                            <p class="text-sm text-slate-600">
                                <i class="fas fa-calendar me-1 text-amber-700"></i>{{ $apt->date->format('d/m/Y H:i') }}
                            </p>
                            @if($apt->reason)
                            <p class="mt-1 text-xs text-slate-600">{{ Str::limit($apt->reason, 80) }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('doctor.appointments.accept', $apt) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                    <i class="fas fa-check"></i>{{ __('app.doctor_dashboard.accept') }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('doctor.appointments.refuse', $apt) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-50">
                                    <i class="fas fa-times"></i>{{ __('app.doctor_dashboard.refuse') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($todayAppointments->isNotEmpty())
    <div class="overflow-hidden rounded-2xl border border-primary-200/60 bg-gradient-to-br from-primary-50/80 to-white shadow-md">
        <div class="border-b border-primary-100 bg-primary-600/5 px-6 py-4">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                    <i class="fas fa-sun"></i>
                </span>
                {{ __('app.doctor_dashboard.today_title') }}
            </h2>
        </div>
        <div class="space-y-3 p-6">
            @foreach($todayAppointments as $apt)
            <div class="flex flex-col gap-3 rounded-xl border border-primary-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-slate-900">{{ $apt->patient->user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $apt->date->format('H:i') }}</p>
                </div>
                <a href="{{ route('doctor.consultation.show', $apt) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">
                    {{ __('app.doctor_dashboard.start_consultation') }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
