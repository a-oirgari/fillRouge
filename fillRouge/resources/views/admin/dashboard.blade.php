@extends('layouts.app')
@section('title', __('app.admin_dashboard.title'))

@section('content')
<div class="space-y-8">
    <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 text-white shadow-md">
            <i class="fas fa-shield-halved"></i>
        </span>
        {{ __('app.admin_dashboard.title') }}
    </h1>

    @php
        $statCards = [
            ['label' => __('app.admin_dashboard.stat_users'), 'value' => $stats['total_users'], 'icon' => 'fa-users', 'wrap' => 'bg-blue-100', 'iconColor' => 'text-blue-700'],
            ['label' => __('app.admin_dashboard.stat_patients'), 'value' => $stats['total_patients'], 'icon' => 'fa-user', 'wrap' => 'bg-violet-100', 'iconColor' => 'text-violet-700'],
            ['label' => __('app.admin_dashboard.stat_doctors_ok'), 'value' => $stats['validated_doctors'], 'icon' => 'fa-user-md', 'wrap' => 'bg-emerald-100', 'iconColor' => 'text-emerald-700'],
            ['label' => __('app.admin_dashboard.stat_pending'), 'value' => $stats['pending_doctors'], 'icon' => 'fa-hourglass-half', 'wrap' => 'bg-amber-100', 'iconColor' => 'text-amber-800'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
        @foreach($statCards as $stat)
        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-white to-slate-50 p-4 shadow-md md:p-5">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="truncate text-xs font-medium text-slate-500 md:text-sm">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900 md:text-3xl">{{ $stat['value'] }}</p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $stat['wrap'] }}">
                    <i class="fas {{ $stat['icon'] }} {{ $stat['iconColor'] }} text-lg md:text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-gradient-to-br from-amber-50/40 to-white shadow-md">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('app.admin_dashboard.validate_title') }}</h2>
                <a href="{{ route('admin.doctors', ['validated' => 0]) }}"
                   class="text-sm font-semibold text-primary-600 hover:text-primary-800">{{ __('app.admin_dashboard.see_all') }}</a>
            </div>
            <div class="p-6">
                @if($pendingDoctors->isEmpty())
                    <p class="py-6 text-center text-slate-500">{{ __('app.admin_dashboard.no_pending_doctors') }}</p>
                @else
                    <div class="space-y-3">
                        @foreach($pendingDoctors as $doctor)
                        <div class="flex flex-col gap-3 rounded-xl border border-amber-100 bg-amber-50/60 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">Dr. {{ $doctor->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $doctor->user->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.doctors.validate', $doctor) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 sm:w-auto">
                                    {{ __('app.admin_dashboard.validate') }}
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white shadow-md">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('app.admin_dashboard.new_users') }}</h2>
                <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-800">{{ __('app.admin_dashboard.see_all') }}</a>
            </div>
            <div class="space-y-3 p-6">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold
                        {{ $user->role === 'doctor' ? 'bg-primary-100 text-primary-800' : ($user->role === 'admin' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-700') }}">
                        {{ __('app.roles.' . $user->role) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @php
        $shortcuts = [
            ['route' => 'admin.doctors', 'label' => __('app.admin_dashboard.shortcut_doctors'), 'icon' => 'fa-user-md', 'wrap' => 'bg-blue-100', 'iconColor' => 'text-blue-700'],
            ['route' => 'admin.users', 'label' => __('app.admin_dashboard.shortcut_users'), 'icon' => 'fa-users', 'wrap' => 'bg-violet-100', 'iconColor' => 'text-violet-700'],
            ['route' => 'admin.statistics', 'label' => __('app.admin_dashboard.shortcut_stats'), 'icon' => 'fa-chart-column', 'wrap' => 'bg-emerald-100', 'iconColor' => 'text-emerald-700'],
            ['route' => 'admin.specialities', 'label' => __('app.admin_dashboard.shortcut_specs'), 'icon' => 'fa-tags', 'wrap' => 'bg-orange-100', 'iconColor' => 'text-orange-700'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
        @foreach($shortcuts as $nav)
        <a href="{{ route($nav['route']) }}"
           class="flex flex-col items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-5 text-center shadow-md transition hover:border-primary-200 hover:shadow-lg">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $nav['wrap'] }}">
                <i class="fas {{ $nav['icon'] }} {{ $nav['iconColor'] }} text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-800">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection
