@extends('layouts.app')
@section('title', __('app.nav.login'))

@section('content')
<div class="flex min-h-[calc(100vh-12rem)] items-center justify-center">
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/90 p-8 shadow-xl">
        <div class="mb-8 text-center">
            <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 text-white shadow-md">
                <i class="fas fa-heartbeat text-2xl"></i>
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('app.auth.login_title') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('app.auth.login_subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.auth.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                       placeholder="exemple@email.com">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('app.auth.password') }}</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-slate-900 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                       placeholder="••••••••">
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    {{ __('app.auth.remember') }}
                </label>
                <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-800">{{ __('app.auth.forgot') }}</a>
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 py-2.5 font-semibold text-white shadow-md transition hover:from-primary-700 hover:to-primary-800">
                {{ __('app.auth.submit_login') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            {{ __('app.auth.no_account') }}
            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-800">{{ __('app.auth.signup_link') }}</a>
        </p>
    </div>
</div>
@endsection
