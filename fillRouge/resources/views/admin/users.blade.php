@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h1>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom ou email..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Rôle</label>
                <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Tous</option>
                    <option value="patient"  {{ request('role') === 'patient'  ? 'selected' : '' }}>Patient</option>
                    <option value="doctor"   {{ request('role') === 'doctor'   ? 'selected' : '' }}>Médecin</option>
                    <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                <i class="fas fa-search mr-1"></i> Filtrer
            </button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.users') }}"
               class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Réinitialiser
            </a>
            @endif
        </form>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Inscrit le</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-4">
                        @php
                            $roleConfig = [
                                'patient' => 'bg-blue-100 text-blue-700',
                                'doctor'  => 'bg-purple-100 text-purple-700',
                                'admin'   => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $roleConfig[$user->role] ?? '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-4">
                        @if($user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                              onsubmit="return confirm('Supprimer le compte de {{ addslashes($user->name) }} ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1 transition">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        @else
                        <span class="text-gray-300 text-xs">Protégé</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">Aucun utilisateur trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>
@endsection