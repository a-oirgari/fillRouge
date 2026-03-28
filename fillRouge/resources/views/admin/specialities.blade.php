@extends('layouts.app')
@section('title', 'Gestion des spécialités')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-tags text-orange-500 mr-2"></i>Spécialités médicales
    </h1>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter une spécialité</h2>
        <form method="POST" action="{{ route('admin.specialities.store') }}" class="flex gap-3">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Ex: Cardiologie, Dermatologie..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-1"></i> Ajouter
            </button>
        </form>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Spécialité</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Médecins</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($specialities as $spec)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-sm text-gray-400">{{ $spec->id }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $spec->name }}</td>
                    <td class="px-5 py-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                            {{ $spec->doctors_count }} médecin(s)
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if($spec->doctors_count === 0)
                        <form method="POST" action="{{ route('admin.specialities.delete', $spec) }}"
                              onsubmit="return confirm('Supprimer la spécialité {{ addslashes($spec->name) }} ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        @else
                        <span class="text-gray-300 text-xs">Non supprimable</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-10 text-gray-400">Aucune spécialité</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $specialities->links() }}</div>
    </div>
</div>
@endsection