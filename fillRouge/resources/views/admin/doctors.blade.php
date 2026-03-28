@extends('layouts.app')
@section('title', 'Gestion des médecins')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des médecins</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.doctors') }}" class="px-4 py-2 rounded-lg text-sm {{ !request('validated') ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">Tous</a>
            <a href="{{ route('admin.doctors', ['validated' => 1]) }}" class="px-4 py-2 rounded-lg text-sm {{ request('validated') == '1' ? 'bg-green-600 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">Validés</a>
            <a href="{{ route('admin.doctors', ['validated' => 0]) }}" class="px-4 py-2 rounded-lg text-sm {{ request('validated') == '0' ? 'bg-amber-500 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">En attente</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Médecin</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Spécialités</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Ville</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($doctors as $doctor)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $doctor->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $doctor->user->email }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        {{ $doctor->specialities->pluck('name')->join(', ') ?: '—' }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">{{ $doctor->city ?: '—' }}</td>
                    <td class="px-5 py-4">
                        @if($doctor->validated)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium">Validé</span>
                        @else
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-xs font-medium">En attente</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            @if(!$doctor->validated)
                            <form method="POST" action="{{ route('admin.doctors.validate', $doctor) }}">
                                @csrf @method('PATCH')
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition">
                                    Valider
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.doctors.invalidate', $doctor) }}">
                                @csrf @method('PATCH')
                                <button class="bg-amber-500 text-white px-3 py-1 rounded text-xs hover:bg-amber-600 transition">
                                    Désactiver
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.users.delete', $doctor->user) }}"
                                  onsubmit="return confirm('Supprimer ce compte ?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">Aucun médecin trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $doctors->links() }}</div>
    </div>
</div>
@endsection