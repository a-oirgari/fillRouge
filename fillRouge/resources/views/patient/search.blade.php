@extends('layouts.app')
@section('title', __('app.search.title'))

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ __('app.search.title') }}</h1>

    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <form method="GET" action="{{ route('doctors.search') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.search.speciality') }}</label>
                <select name="speciality_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary-500 outline-none">
                    <option value="">{{ __('app.search.all_specialities') }}</option>
                    @foreach($specialities as $spec)
                        <option value="{{ $spec->id }}" {{ request('speciality_id') == $spec->id ? 'selected' : '' }}>
                            {{ __('app.specialities.' . $spec->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.search.city') }}</label>
                <input type="text" name="city" value="{{ request('city') }}" list="cities-list"
                       placeholder="{{ __('app.search.city_placeholder') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary-500 outline-none">
                <datalist id="cities-list">
                    @if(isset($cities))
                        @foreach($cities as $c)
                            <option value="{{ trans()->has('cities.'.$c) ? __('cities.'.$c) : $c }}">
                        @endforeach
                    @endif
                </datalist>
            </div>

            <button type="submit"
                    class="bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-primary-700 transition flex items-center gap-2">
                <i class="fas fa-search"></i> {{ __('app.search.search_btn') }}
            </button>
        </form>
    </div>

    
    <div id="doctors-list-container">
        <p class="text-sm text-gray-500 mb-4">{{ __('app.search.doctors_found', ['count' => $doctors->total()]) }}</p>

        @if($doctors->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-user-md text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">{{ __('app.search.no_doctors') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($doctors as $doctor)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            @if($doctor->photo)
                                <img src="{{ asset('storage/'.$doctor->photo) }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <i class="fas fa-user-md text-primary-600 text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Dr. {{ $doctor->user->name }}</h3>
                            <p class="text-sm text-primary-600">
                                {{ $doctor->specialities->map(function($s) { return __('app.specialities.' . $s->name); })->join(', ') ?: __('app.search.generalist') }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1 text-sm text-gray-500 mb-4">
                        @if($doctor->city)
                        <p><i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>{{ trans()->has('cities.'.$doctor->city) ? __('cities.'.$doctor->city) : $doctor->city }}</p>
                        @endif
                        @if($doctor->bio)
                        <p class="line-clamp-2 text-gray-600">{{ $doctor->bio }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('doctors.show', $doctor) }}"
                           class="flex-1 text-center border border-primary-600 text-primary-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-primary-50 transition">
                            {{ __('app.search.view_profile') }}
                        </a>
                        @auth
                            @if(auth()->user()->isPatient())
                            <a href="{{ route('doctors.show', $doctor) }}#book"
                               class="flex-1 text-center bg-primary-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                                {{ __('app.search.book_appointment') }}
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $doctors->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    let timeout = null;
    const searchForm = document.querySelector('form');
    const container = document.getElementById('doctors-list-container');
    const inputs = searchForm.querySelectorAll('input, select');

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const url = new URL(searchForm.action);
                const params = new URLSearchParams(new FormData(searchForm));
                url.search = params.toString();

                // Add a visual loading state or opacity here if needed
                container.style.opacity = '0.5';

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // If we want to return just partial, but full HTML is fine
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContainer = doc.getElementById('doctors-list-container');
                    if(newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                    }
                    container.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error fetching doctors:', error);
                    container.style.opacity = '1';
                });
            }, 300); // 300ms debounce
        });
    });
</script>
@endpush