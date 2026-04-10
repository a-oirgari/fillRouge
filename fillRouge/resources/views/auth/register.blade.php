@extends('layouts.app')
@section('title', __('app.register.title'))

@section('content')
<div class="flex items-center justify-center py-8">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/90 p-8 shadow-xl">
        <div class="mb-8 text-center">
            <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 text-white shadow-md">
                <i class="fas fa-heartbeat text-2xl"></i>
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('app.register.title') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('app.register.subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm" x-data="{ role: '{{ old('role', 'patient') }}' }">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">{{ __('app.register.i_am') }}</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3 transition"
                           :class="role === 'patient' ? 'border-primary-500 bg-primary-50' : 'border-slate-200'">
                        <input type="radio" name="role" value="patient" x-model="role" class="hidden">
                        <i class="fas fa-user text-primary-600"></i>
                        <span class="font-medium">{{ __('app.register.patient') }}</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3 transition"
                           :class="role === 'doctor' ? 'border-primary-500 bg-primary-50' : 'border-slate-200'">
                        <input type="radio" name="role" value="doctor" x-model="role" class="hidden">
                        <i class="fas fa-user-md text-primary-600"></i>
                        <span class="font-medium">{{ __('app.register.doctor') }}</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.full_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.password') }}</label>
                    <input type="password" name="password" required
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.confirm') }}</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
            </div>

            <div x-show="role === 'patient'" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.address') }}</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
            </div>

            <div x-show="role === 'doctor'" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.city') }}</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.register.bio') }}</label>
                    <textarea name="bio" rows="3"
                              class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">{{ old('bio') }}</textarea>
                </div>
                <p class="rounded-xl border border-amber-200/80 bg-amber-50 p-3 text-xs text-amber-900">
                    <i class="fas fa-circle-info me-1"></i>
                    {{ __('app.register.doctor_note') }}
                </p>
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 py-2.5 font-semibold text-white shadow-md transition hover:from-primary-700 hover:to-primary-800">
                {{ __('app.register.submit') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            {{ __('app.register.already') }}
            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-800">{{ __('app.register.login_link') }}</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
