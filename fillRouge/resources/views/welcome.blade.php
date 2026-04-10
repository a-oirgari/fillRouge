@extends('layouts.app')
@section('title', __('welcome.page_title'))

@section('content')

<div class="text-center py-16 md:py-20">
    <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-5 tracking-tight">
        {{ __('welcome.hero_title_before') }} <span class="text-primary-600">{{ __('welcome.hero_title_highlight') }}</span>
    </h1>
    <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
        <a href="#section-trouver-medecin"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            {{ __('welcome.hero_link_consult') }}</a><span class="text-slate-500">,</span>
        <a href="#section-rendez-vous"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            {{ __('welcome.hero_link_book') }}</a>
        <span class="text-slate-500"> {{ __('welcome.hero_and') }} </span>
        <a href="#section-ordonnance"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            {{ __('welcome.hero_link_rx') }}</a><span class="text-slate-500">.</span>
    </p>
    <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('doctors.search') }}"
           class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-8 py-3 text-lg font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            {{ __('welcome.cta_find_doctor') }}
        </a>
        <a href="{{ route('register') }}"
           class="inline-flex items-center justify-center rounded-xl border-2 border-primary-600 bg-white px-8 py-3 text-lg font-semibold text-primary-700 transition hover:bg-primary-50">
            {{ __('welcome.cta_register') }}
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-12 scroll-mt-24">
    <a id="section-trouver-medecin" href="{{ route('doctors.search') }}"
       class="group block rounded-2xl border border-slate-300/50 bg-white/90 p-6 text-center shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm transition hover:border-primary-300 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 transition group-hover:bg-primary-50">
            <i class="fas fa-search text-2xl text-primary-700"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900 group-hover:text-primary-800">{{ __('welcome.card_find_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('welcome.card_find_desc') }}</p>
        <span class="mt-4 inline-block text-sm font-medium text-primary-600 opacity-0 transition group-hover:opacity-100">{{ __('welcome.card_find_hover') }} →</span>
    </a>

    <div id="section-rendez-vous"
         class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 text-center shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm transition hover:border-emerald-300/80 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100/80">
            <i class="fas fa-calendar-check text-2xl text-emerald-700"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ __('welcome.card_book_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('welcome.card_book_desc') }}</p>
    </div>

    <div id="section-ordonnance"
         class="rounded-2xl border border-slate-300/50 bg-white/90 p-6 text-center shadow-md ring-1 ring-slate-900/5 backdrop-blur-sm transition hover:border-violet-300/80 hover:shadow-lg">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-violet-100/80">
            <i class="fas fa-file-medical text-2xl text-violet-700"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-900">{{ __('welcome.card_rx_title') }}</h3>
        <p class="text-sm leading-relaxed text-slate-600">{{ __('welcome.card_rx_desc') }}</p>
    </div>
</div>


<div class="rounded-2xl bg-gradient-to-br from-primary-700 to-primary-900 p-8 text-center text-white shadow-xl ring-1 ring-primary-900/20">
    <h2 class="mb-2 text-2xl font-bold tracking-tight">{{ __('welcome.cta_doctor_title') }}</h2>
    <p class="mb-6 text-primary-100/95">{{ __('welcome.cta_doctor_desc') }}</p>
    <a href="{{ route('register') }}"
       class="inline-block rounded-xl bg-white px-6 py-3 font-semibold text-primary-800 shadow-md transition hover:bg-primary-50">
        {{ __('welcome.cta_doctor_button') }}
    </a>
</div>
@endsection
