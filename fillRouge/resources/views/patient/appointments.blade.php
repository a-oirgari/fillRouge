@extends('layouts.app')
@section('title', __('app.appointments.title'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('app.appointments.title') }}</h1>
        <a href="{{ route('doctors.search') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> {{ __('app.appointments.new') }}
        </a>
    </div>

    
    <div class="flex gap-2 flex-wrap">
        @foreach(['all' => __('app.appointments.all'), 'pending' => __('app.appointments.status_pending'), 'accepted' => __('app.appointments.status_accepted'), 'refused' => __('app.appointments.status_refused'), 'completed' => __('app.appointments.status_completed')] as $value => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $value === 'all' ? null : $value]) }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium transition
               {{ (request('status') === $value || ($value === 'all' && !request('status')))
                   ? 'bg-blue-600 text-white'
                   : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    
    @if($appointments->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-calendar-xmark text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">{{ __('app.appointments.no_appointments') }}</p>
            <a href="{{ route('doctors.search') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
                {{ __('app.appointments.book_one') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($appointments as $apt)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-md text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Dr. {{ $apt->doctor->user->name }}</h3>
                            <p class="text-sm text-blue-600">
                                {{ $apt->doctor->specialities->pluck('name')->join(', ') ?: __('app.appointments.generalist') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-calendar mr-1"></i>{{ $apt->date->format('d/m/Y') }}
                                <span class="mx-2">·</span>
                                <i class="fas fa-clock mr-1"></i>{{ $apt->date->format('H:i') }}
                            </p>
                            @if($apt->reason)
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fas fa-comment mr-1"></i>{{ Str::limit($apt->reason, 80) }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @php
                            $statusConfig = [
                                'pending'   => ['label' => __('app.appointments.status_pending'), 'class' => 'bg-yellow-100 text-yellow-700'],
                                'accepted'  => ['label' => __('app.appointments.status_accepted'),    'class' => 'bg-green-100 text-green-700'],
                                'refused'   => ['label' => __('app.appointments.status_refused'),     'class' => 'bg-red-100 text-red-700'],
                                'completed' => ['label' => __('app.appointments.status_completed'),    'class' => 'bg-gray-100 text-gray-700'],
                            ];
                            $config = $statusConfig[$apt->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
                            {{ $config['label'] }}
                        </span>

                        @if($apt->status === 'completed' && $apt->consultation)
                        <a href="{{ route('patient.history') }}"
                           class="text-blue-600 text-xs hover:underline flex items-center gap-1">
                            <i class="fas fa-file-medical"></i> {{ __('app.appointments.see_diagnostic') }}
                        </a>
                        @endif

                        @if(in_array($apt->status, ['accepted', 'pending']))
                        <a href="{{ route('messages.conversation', $apt->doctor->user) }}"
                           class="text-gray-500 text-xs hover:text-blue-600 flex items-center gap-1">
                            <i class="fas fa-comment"></i> {{ __('app.appointments.contact_doctor') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $appointments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection