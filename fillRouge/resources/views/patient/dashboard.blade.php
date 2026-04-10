@extends('layouts.app')
@section('title', __('app.patient_dashboard.title'))

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
                {{ __('app.patient_dashboard.greeting', ['name' => auth()->user()->name]) }}
            </h1>
            <p class="text-slate-600">{{ __('app.patient_dashboard.subtitle') }}</p>
        </div>
        <a href="{{ route('doctors.search') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-primary-700 hover:to-primary-800">
            <i class="fas fa-calendar-plus"></i> {{ __('app.patient_dashboard.cta_book') }}
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50/90 shadow-md">
        <div class="border-b border-slate-100 bg-primary-600/5 px-6 py-4">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                    <i class="fas fa-calendar-check"></i>
                </span>
                {{ __('app.patient_dashboard.upcoming') }}
            </h2>
        </div>
        <div class="p-6">
            @if($upcomingAppointments->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/80 py-12 text-center text-slate-500">
                    <span class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-200/80 text-slate-500">
                        <i class="fas fa-calendar-xmark text-2xl"></i>
                    </span>
                    <p class="font-medium">{{ __('app.patient_dashboard.no_upcoming') }}</p>
                    <a href="{{ route('doctors.search') }}" class="mt-3 inline-block text-sm font-semibold text-primary-600 hover:text-primary-800">
                        {{ __('app.patient_dashboard.find_doctor') }}
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingAppointments as $apt)
                    <div class="flex flex-col gap-3 rounded-xl border border-slate-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-100 text-primary-700">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">Dr. {{ $apt->doctor->user->name }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ $apt->doctor->specialities->pluck('name')->join(', ') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-start sm:text-end">
                            <p class="text-sm font-semibold text-slate-800">{{ $apt->date->format('d/m/Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $apt->date->format('H:i') }}</p>
                            <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $apt->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                {{ __('app.appointment_status.' . $apt->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('patient.appointments') }}" class="mt-5 inline-block text-sm font-semibold text-primary-600 hover:text-primary-800">
                    {{ __('app.patient_dashboard.see_all_apt') }} →
                </a>
            @endif
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-gradient-to-br from-violet-50/50 via-white to-slate-50/90 shadow-md">
        <div class="border-b border-slate-100 bg-violet-600/5 px-6 py-4">
            <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                    <i class="fas fa-clock-rotate-left"></i>
                </span>
                {{ __('app.patient_dashboard.history') }}
            </h2>
        </div>
        <div class="p-6">
            @if($recentHistory->isEmpty())
                <p class="py-8 text-center text-slate-500">{{ __('app.patient_dashboard.no_history') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($recentHistory as $apt)
                    <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-white/90 p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">Dr. {{ $apt->doctor->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $apt->date->format('d/m/Y') }}</p>
                            @if($apt->consultation)
                                <p class="mt-1 line-clamp-1 text-xs text-slate-600">
                                    <i class="fas fa-stethoscope me-1 text-violet-600"></i>
                                    {{ Str::limit($apt->consultation->diagnostic, 60) }}
                                </p>
                            @endif
                        </div>
                        <span class="inline-flex w-fit rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('app.patient_dashboard.done') }}</span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('patient.history') }}" class="mt-5 inline-block text-sm font-semibold text-primary-600 hover:text-primary-800">
                    {{ __('app.patient_dashboard.see_full_history') }} →
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
