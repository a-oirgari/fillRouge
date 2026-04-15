@extends('layouts.app')
@section('title', 'Dr. ' . $doctor->user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                @if($doctor->photo)
                    <img src="{{ asset('storage/'.$doctor->photo) }}" class="w-24 h-24 rounded-full object-cover">
                @else
                    <i class="fas fa-user-md text-primary-600 text-4xl"></i>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">Dr. {{ $doctor->user->name }}</h1>
                <p class="text-primary-600 font-medium mt-1">
                    {{ $doctor->specialities->pluck('name')->join(' · ') ?: __('app.profile.generalist') }}
                </p>
                @if($doctor->city)
                <p class="text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ trans()->has('cities.'.$doctor->city) ? __('cities.'.$doctor->city) : $doctor->city }}</p>
                @endif
                @if($doctor->phone)
                <p class="text-gray-500 mt-1"><i class="fas fa-phone mr-1"></i>{{ $doctor->phone }}</p>
                @endif
                @if($doctor->bio)
                <p class="text-gray-600 mt-3">{{ $doctor->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    
    @if($doctor->availabilities->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-clock text-green-500 mr-2"></i>{{ __('app.profile.availabilities') }}
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($doctor->availabilities as $avail)
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="font-medium text-green-800 capitalize">{{ $avail->day }}</p>
                <p class="text-sm text-green-600">{{ $avail->start_time }} - {{ $avail->end_time }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    
    @auth
        @if(auth()->user()->isPatient())
        <div id="book" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-plus text-primary-500 mr-2"></i>{{ __('app.profile.book_title') }}
            </h2>
            <form method="POST" action="{{ route('patient.book', $doctor) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.profile.datetime') }}</label>
                    <input type="datetime-local" name="date" required
                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                           step="1800"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.profile.reason') }}</label>
                    <textarea name="reason" rows="3" placeholder="{{ __('app.profile.reason_placeholder') }}"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary-500 outline-none resize-none"></textarea>
                </div>
                <button type="submit"
                        class="bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary-700 transition">
                    {{ __('app.profile.send_request') }}
                </button>
            </form>
        </div>
        @endif
    @else
    <div class="bg-primary-50 rounded-2xl p-6 text-center">
        <p class="text-primary-700 mb-3">{{ __('app.profile.login_to_book') }}</p>
        <a href="{{ route('login') }}" class="bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary-700 transition inline-block">
            {{ __('app.profile.login_btn') }}
        </a>
    </div>
    @endauth
</div>
@endsection