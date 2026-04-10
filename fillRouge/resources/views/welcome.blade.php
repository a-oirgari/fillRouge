@extends('layouts.app')
@section('title', 'MediConnect - Téléconsultation médicale')

@section('content')

<div class="text-center py-16 md:py-20">
    <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-5 tracking-tight">
        La santé, à portée de <span class="text-primary-600">clic</span>
    </h1>
    <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
        <a href="#section-trouver-medecin"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            Consultez un médecin qualifié en ligne</a><span class="text-slate-500">,</span>
        <a href="#section-rendez-vous"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            prenez des rendez-vous</a>
        <span class="text-slate-500"> et </span>
        <a href="#section-ordonnance"
           class="text-primary-600 font-medium underline decoration-primary-200 underline-offset-4 hover:decoration-primary-600 hover:text-primary-700 transition">
            recevez vos ordonnances depuis chez vous</a><span class="text-slate-500">.</span>
    </p>
    <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('doctors.search') }}"
           class="inline-flex items-center justify-center rounded-xl bg-primary-600 px-8 py-3 text-lg font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
            Trouver un médecin
        </a>
        <a href="{{ route('register') }}"
           class="inline-flex items-center justify-center rounded-xl border-2 border-primary-600 bg-white px-8 py-3 text-lg font-semibold text-primary-700 transition hover:bg-primary-50">
            Créer un compte
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-12 scroll-mt-24">
    <a id="section-trouver-medecin" href="{{ route('doctors.search') }}"
       class="group block rounded-2xl border border-slate-200/80 bg-white p-6 text-center shadow-sm transition hover:border-primary-200 hover:shadow-md">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 transition group-hover:bg-blue-50">
            <i class="fas fa-search text-2xl text-blue-600"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-800 group-hover:text-primary-700">Trouvez votre médecin</h3>
        <p class="text-sm leading-relaxed text-slate-600">Recherchez par spécialité et ville parmi nos médecins partenaires.</p>
        <span class="mt-4 inline-block text-sm font-medium text-primary-600 opacity-0 transition group-hover:opacity-100">Rechercher →</span>
    </a>

    <div id="section-rendez-vous"
         class="rounded-2xl border border-slate-200/80 bg-white p-6 text-center shadow-sm transition hover:border-emerald-200 hover:shadow-md">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">
            <i class="fas fa-calendar-check text-2xl text-emerald-600"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-800">Prenez rendez-vous</h3>
        <p class="text-sm leading-relaxed text-slate-600">Réservez en quelques clics selon les disponibilités du médecin.</p>
    </div>

    <div id="section-ordonnance"
         class="rounded-2xl border border-slate-200/80 bg-white p-6 text-center shadow-sm transition hover:border-violet-200 hover:shadow-md">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-violet-100">
            <i class="fas fa-file-medical text-2xl text-violet-600"></i>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-slate-800">Recevez votre ordonnance</h3>
        <p class="text-sm leading-relaxed text-slate-600">Consultez votre diagnostic et ordonnance en ligne à tout moment.</p>
    </div>
</div>


<div class="rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-center text-white shadow-lg">
    <h2 class="mb-2 text-2xl font-bold tracking-tight">Vous êtes médecin ?</h2>
    <p class="mb-6 text-primary-100">Rejoignez MediConnect et développez votre patientèle en ligne.</p>
    <a href="{{ route('register') }}"
       class="inline-block rounded-xl bg-white px-6 py-3 font-semibold text-primary-700 shadow-sm transition hover:bg-primary-50">
        Créer mon espace médecin
    </a>
</div>
@endsection
