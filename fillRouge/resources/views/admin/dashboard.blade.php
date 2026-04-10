@extends('layouts.app')
@section('title', __('admin.dashboard.title'))

@section('content')
@php
    $statsUi = [
        ['label' => __('admin.dashboard.stat_users'), 'value' => $stats['total_users'], 'icon' => 'fa-users', 'iconWrap' => 'bg-blue-100', 'iconText' => 'text-blue-700'],
        ['label' => __('admin.dashboard.stat_patients'), 'value' => $stats['total_patients'], 'icon' => 'fa-user', 'iconWrap' => 'bg-violet-100', 'iconText' => 'text-violet-700'],
        ['label' => __('admin.dashboard.stat_validated'), 'value' => $stats['validated_doctors'], 'icon' => 'fa-user-md', 'iconWrap' => 'bg-emerald-100', 'iconText' => 'text-emerald-800'],
        ['label' => __('admin.dashboard.stat_pending'), 'value' => $stats['pending_doctors'], 'icon' => 'fa-hourglass-half', 'iconWrap' => 'bg-amber-100', 'iconText' => 'text-amber-800'],
    ];
    $shortcuts = [
        ['route' => 'admin.doctors', 'label' => __('admin.dashboard.shortcut_doctors'), 'icon' => 'fa-user-md', 'iconWrap' => 'bg-blue-100', 'iconText' => 'text-blue-700'],
        ['route' => 'admin.users', 'label' => __('admin.dashboard.shortcut_users'), 'icon' => 'fa-users', 'iconWrap' => 'bg-violet-100', 'iconText' => 'text-violet-700'],
        ['route' => 'admin.statistics', 'label' => __('admin.dashboard.shortcut_stats'), 'icon' => 'fa-chart-column', 'iconWrap' => 'bg-emerald-100', 'iconText' => 'text-emerald-800'],
        ['route' => 'admin.specialities', 'label' => __('admin.dashboard.shortcut_specialities'), 'icon' => 'fa-tags', 'iconWrap' => 'bg-orange-100', 'iconText' => 'text-orange-800'],
    ];
@endphp

<div class="space-y-8">
    <h1 class="flex items-center gap-3 border-l-4 border-primary-600 ps-4 text-2xl font-bold tracking-tight text-slate-900 rtl:border-l-0 rtl:border-r-4 rtl:pe-4 rtl:ps-0">
        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-white shadow-sm">
            <i class="fas fa-shield-halved" aria-hidden="true"></i>
        </span>
        {{ __('admin.dashboard.title') }}
    </h1>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach($statsUi as $stat)
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-5 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 sm:text-sm">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 sm:text-3xl">{{ $stat['value'] }}</p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $stat['iconWrap'] }} {{ $stat['iconText'] }}">
                    <i class="fas {{ $stat['icon'] }} text-lg"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="mb-4 flex items-center justify-between border-b border-slate-200/80 pb-3">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.dashboard.validate_section') }}</h2>
                <a href="{{ route('admin.doctors', ['validated' => 0]) }}"
                   class="text-sm font-medium text-primary-600 hover:text-primary-800">{{ __('admin.dashboard.see_all') }}</a>
            </div>
            @if($pendingDoctors->isEmpty())
                <p class="rounded-xl bg-slate-50/80 py-8 text-center text-slate-500">{{ __('admin.dashboard.no_pending_doctors') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($pendingDoctors as $doctor)
                    <div class="flex flex-col gap-3 rounded-xl border border-amber-200/60 bg-amber-50/50 p-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-medium text-slate-900">Dr. {{ $doctor->user->name }}</p>
                            <p class="text-xs text-slate-600">{{ $doctor->user->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.doctors.validate', $doctor) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800 sm:w-auto">
                                {{ __('admin.dashboard.validate') }}
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm">
            <div class="mb-4 flex items-center justify-between border-b border-slate-200/80 pb-3">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.dashboard.new_users') }}</h2>
                <a href="{{ route('admin.users') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">{{ __('admin.dashboard.see_all') }}</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-200/80 bg-slate-50/50 p-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200/80 text-slate-700">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium
                        {{ $user->role === 'doctor' ? 'bg-primary-100 text-primary-800' : ($user->role === 'admin' ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-800') }}">
                        {{ __('role.'.$user->role) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach($shortcuts as $nav)
        <a href="{{ route($nav['route']) }}"
           class="flex flex-col items-center gap-3 rounded-2xl border border-slate-300/50 bg-white/90 p-5 text-center shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm transition hover:border-primary-200 hover:shadow-lg">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $nav['iconWrap'] }} {{ $nav['iconText'] }}">
                <i class="fas {{ $nav['icon'] }} text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-800">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection
