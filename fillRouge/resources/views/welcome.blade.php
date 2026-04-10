@extends('layouts.app')
@section('title', __('app.meta.default_title'))

@section('content')

<div class="text-center py-14 md:py-20">
    <h1 class="mb-5 text-4xl font-bold tracking-tight text-slate-900 md:text-5xl">
        {{ __('app.welcome.title_prefix') }} <span class="text-primary-600">{{ __('app.welcome.title_highlight') }}</span>
    </h1>
    <p class="mx-auto mb-10 max-w-2xl text-lg leading-relaxed text-slate-600 md:text-xl">
        <a href="#section-trouver-medecin"
           class="font-medium text-primary-600 underline decoration-primary-200 underline-offset-4 transition hover:decoration-primary-600 hover:text-primary-800">
            {{ __('app.welcome.hero_line1') }}</a><span class="text-slate-500">{{ __('app.welcome.punct_comma') }}</span>
        <a href="#section-rendez-vous"
           class="font-medium text-primary-600 underline decoration-primary-200 underline-offset-4 transition hover:decoration-primary-600 hover:text-primary-800">
            {{ __('app.welcome.hero_line2') }}</a>
        <span class="text-slate-500"> {{ __('app.welcome.hero_and') }} </span>
        <a href="#section-ordonnance"
           class="font-medium text-primary-600 underline decoration-primary-200 underline-offset-4 transition hover:decoration-primary-600 hover:text-primary-800">
            {{ __('app.welcome.hero_line3') }}</a><span class="text-slate-500">.</span>
    </p>
    <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('doctors.search') }}"
           class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-3 text-lg font-semibold text-white shadow-lg transition hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            {{ __('app.welcome.cta_find') }}
        </a>
        <a href="{{ route('register') }}"
           class="inline-flex items-center justify-center rounded-xl border-2 border-primary-600 bg-white/90 px-8 py-3 text-lg font-semibold text-primary-800 shadow-sm transition hover:bg-primary-50">
            {{ __('app.welcome.cta_account') }}
        </a>
    </div>
</div>


<div class="grid scroll-mt-24 grid-cols-1 gap-6 py-12 md:grid-cols-3">
    <a id="section-trouver-medecin" href="{{ route('doctors.search') }}"
       class="group block rounded-2xl border border-slate-200/90 bg-gradient-to-b from-white to-slate-50/90 p-6 text-center shadow-md transition hover:border-primary-200 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 transition group-hover:bg-blue-50">
            <i class="fas fa-magnifying-glass text-2xl"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900 group-hover:text-primary-800">{{ __('app.welcome.card_find_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('app.welcome.card_find_desc') }}</p>
        <span class="mt-4 inline-block text-sm font-semibold text-primary-600 opacity-0 transition group-hover:opacity-100">{{ __('app.welcome.card_find_go') }} →</span>
    </a>

    <div id="section-rendez-vous"
         class="rounded-2xl border border-slate-200/90 bg-gradient-to-b from-emerald-50/80 to-white p-6 text-center shadow-md transition hover:border-emerald-200 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
            <i class="fas fa-calendar-check text-2xl"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ __('app.welcome.card_book_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('app.welcome.card_book_desc') }}</p>
    </div>

    <div id="section-ordonnance"
         class="rounded-2xl border border-slate-200/90 bg-gradient-to-b from-violet-50/80 to-white p-6 text-center shadow-md transition hover:border-violet-200 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
            <i class="fas fa-file-medical text-2xl"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ __('app.welcome.card_rx_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('app.welcome.card_rx_desc') }}</p>
    </div>
</div>


<div class="rounded-2xl bg-gradient-to-br from-primary-700 via-primary-800 to-slate-900 p-8 text-center text-white shadow-xl">
    <h2 class="mb-2 text-2xl font-bold tracking-tight">{{ __('app.welcome.doctor_banner_title') }}</h2>
    <p class="mb-6 text-primary-100/95">{{ __('app.welcome.doctor_banner_text') }}</p>
    <a href="{{ route('register') }}"
       class="inline-block rounded-xl bg-white px-6 py-3 font-semibold text-primary-800 shadow-lg transition hover:bg-primary-50">
        {{ __('app.welcome.doctor_banner_cta') }}
    </a>
</div>
@endsection
