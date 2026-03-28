@extends('layouts.app')
@section('title', 'MediConnect - Téléconsultation médicale')

@section('content')

<div class="text-center py-20">
    <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-sm font-medium mb-6">
        <i class="fas fa-shield-alt"></i> Plateforme médicale certifiée
    </div>
    <h1 class="text-5xl font-bold text-gray-900 mb-4">
        La santé, à portée de <span class="text-blue-600">clic</span>
    </h1>
    <p class="text-xl text-gray-500 mb-8 max-w-2xl mx-auto">
        Consultez un médecin qualifié en ligne, prenez des rendez-vous et recevez vos ordonnances depuis chez vous.
    </p>
    <div class="flex justify-center gap-4">
        <a href="{{ route('doctors.search') }}"
           class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-blue-700 transition text-lg">
            Trouver un médecin
        </a>
        <a href="{{ route('register') }}"
           class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-xl font-medium hover:bg-blue-50 transition text-lg">
            Créer un compte
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 py-12">
    @foreach([
        ['icon' => 'fa-search', 'title' => 'Trouvez votre médecin', 'desc' => 'Recherchez par spécialité et ville parmi nos médecins certifiés.', 'color' => 'blue'],
        ['icon' => 'fa-calendar-check', 'title' => 'Prenez rendez-vous', 'desc' => 'Réservez en quelques clics selon les disponibilités du médecin.', 'color' => 'green'],
        ['icon' => 'fa-file-medical', 'title' => 'Recevez votre ordonnance', 'desc' => 'Consultez votre diagnostic et ordonnance en ligne à tout moment.', 'color' => 'purple'],
    ] as $feature)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <div class="w-14 h-14 bg-{{ $feature['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas {{ $feature['icon'] }} text-{{ $feature['color'] }}-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
        <p class="text-gray-500 text-sm">{{ $feature['desc'] }}</p>
    </div>
    @endforeach
</div>


<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-8 text-white text-center">
    <h2 class="text-2xl font-bold mb-2">Vous êtes médecin ?</h2>
    <p class="text-blue-100 mb-5">Rejoignez MediConnect et développez votre patientèle en ligne.</p>
    <a href="{{ route('register') }}"
       class="bg-white text-blue-600 px-6 py-3 rounded-xl font-medium hover:bg-blue-50 transition inline-block">
        Créer mon espace médecin
    </a>
</div>
@endsection