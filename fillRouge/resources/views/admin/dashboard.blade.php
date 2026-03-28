@extends('layouts.app')
@section('title', 'Administration')

@section('content')
<div class="space-y-8">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>Tableau de bord Admin
    </h1>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Utilisateurs', 'value' => $stats['total_users'], 'icon' => 'fa-users', 'color' => 'blue'],
            ['label' => 'Patients', 'value' => $stats['total_patients'], 'icon' => 'fa-user', 'color' => 'purple'],
            ['label' => 'Médecins validés', 'value' => $stats['validated_doctors'], 'icon' => 'fa-user-md', 'color' => 'green'],
            ['label' => 'En attente', 'value' => $stats['pending_doctors'], 'icon' => 'fa-clock', 'color' => 'yellow'],
        ] as $stat)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</p>
                </div>
                <div class="w-12 h-12 bg-{{ $stat['color'] }}-100 rounded-full flex items-center justify-center">
                    <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Médecins à valider</h2>
                <a href="{{ route('admin.doctors', ['validated' => 0]) }}"
                   class="text-blue-600 text-sm hover:underline">Voir tous</a>
            </div>
            @if($pendingDoctors->isEmpty())
                <p class="text-gray-400 text-center py-6">Aucun médecin en attente</p>
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
                                Valider
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Nouveaux inscrits</h2>
                <a href="{{ route('admin.users') }}" class="text-blue-600 text-sm hover:underline">Voir tous</a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $user->role === 'doctor' ? 'bg-blue-100 text-blue-700' : ($user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

   
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['route' => 'admin.doctors', 'label' => 'Gérer médecins', 'icon' => 'fa-user-md', 'color' => 'blue'],
            ['route' => 'admin.users', 'label' => 'Gérer utilisateurs', 'icon' => 'fa-users', 'color' => 'purple'],
            ['route' => 'admin.statistics', 'label' => 'Statistiques', 'icon' => 'fa-chart-bar', 'color' => 'green'],
            ['route' => 'admin.specialities', 'label' => 'Spécialités', 'icon' => 'fa-tags', 'color' => 'orange'],
        ] as $nav)
        <a href="{{ route($nav['route']) }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition flex flex-col items-center gap-3 text-center">
            <div class="w-12 h-12 bg-{{ $nav['color'] }}-100 rounded-full flex items-center justify-center">
                <i class="fas {{ $nav['icon'] }} text-{{ $nav['color'] }}-600 text-xl"></i>
            </div>
            <span class="text-sm font-medium text-gray-700">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection