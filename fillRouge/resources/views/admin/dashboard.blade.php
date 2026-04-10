@extends('layouts.app')
@section('title', __('admin.dashboard.meta_title'))

@section('content')
@php
    $statsUi = [
        ['label' => __('admin.dashboard.stat_users'), 'value' => $stats['total_users'], 'icon' => 'fa-users', 'wrap' => 'bg-blue-100', 'ic' => 'text-blue-600'],
        ['label' => __('admin.dashboard.stat_patients'), 'value' => $stats['total_patients'], 'icon' => 'fa-user', 'wrap' => 'bg-purple-100', 'ic' => 'text-purple-600'],
        ['label' => __('admin.dashboard.stat_validated'), 'value' => $stats['validated_doctors'], 'icon' => 'fa-user-md', 'wrap' => 'bg-green-100', 'ic' => 'text-green-600'],
        ['label' => __('admin.dashboard.stat_pending'), 'value' => $stats['pending_doctors'], 'icon' => 'fa-clock', 'wrap' => 'bg-yellow-100', 'ic' => 'text-yellow-600'],
    ];
    $navUi = [
        ['route' => 'admin.doctors', 'label' => __('admin.dashboard.shortcut_doctors'), 'icon' => 'fa-user-md', 'wrap' => 'bg-blue-100', 'ic' => 'text-blue-600'],
        ['route' => 'admin.users', 'label' => __('admin.dashboard.shortcut_users'), 'icon' => 'fa-users', 'wrap' => 'bg-purple-100', 'ic' => 'text-purple-600'],
        ['route' => 'admin.statistics', 'label' => __('admin.dashboard.shortcut_stats'), 'icon' => 'fa-chart-bar', 'wrap' => 'bg-green-100', 'ic' => 'text-green-600'],
        ['route' => 'admin.specialities', 'label' => __('admin.dashboard.shortcut_specialities'), 'icon' => 'fa-tags', 'wrap' => 'bg-orange-100', 'ic' => 'text-orange-600'],
    ];
@endphp

<div class="space-y-8">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-shield-alt text-primary-600 mr-2"></i>{{ __('admin.dashboard.title') }}
    </h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($statsUi as $stat)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</p>
                </div>
                <div class="w-12 h-12 {{ $stat['wrap'] }} rounded-full flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} {{ $stat['ic'] }} text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('admin.dashboard.validate_section') }}</h2>
                <a href="{{ route('admin.doctors', ['validated' => 0]) }}"
                   class="text-primary-600 text-sm hover:underline">{{ __('admin.dashboard.see_all') }}</a>
            </div>
            @if($pendingDoctors->isEmpty())
                <p class="text-gray-400 text-center py-6">{{ __('admin.dashboard.no_pending_doctors') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($pendingDoctors as $doctor)
                    <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl">
                        <div>
                            <p class="font-medium text-gray-800">Dr. {{ $doctor->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $doctor->user->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.doctors.validate', $doctor) }}">
                            @csrf @method('PATCH')
                            <button class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-green-600 transition">
                                {{ __('admin.dashboard.validate') }}
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('admin.dashboard.new_users') }}</h2>
                <a href="{{ route('admin.users') }}" class="text-primary-600 text-sm hover:underline">{{ __('admin.dashboard.see_all') }}</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-primary-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $user->role === 'doctor' ? 'bg-primary-100 text-primary-700' : ($user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ __('role.'.$user->role) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($navUi as $nav)
        <a href="{{ route($nav['route']) }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5 hover:shadow-md transition flex flex-col items-center gap-3 text-center">
            <div class="w-12 h-12 {{ $nav['wrap'] }} rounded-full flex items-center justify-center">
                <i class="fas {{ $nav['icon'] }} {{ $nav['ic'] }} text-xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection
