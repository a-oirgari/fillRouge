@extends('layouts.app')
@section('title', 'Inscription')

@section('content')
<div class="flex items-center justify-center py-8">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-heartbeat text-blue-600 text-4xl mb-3"></i>
            <h1 class="text-2xl font-bold text-gray-800">Créer un compte</h1>
            <p class="text-gray-500 text-sm mt-1">Rejoignez MediConnect</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm" x-data="{ role: '{{ old('role', 'patient') }}' }">
            @csrf

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Je suis</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 border-2 rounded-lg p-3 cursor-pointer transition"
                           :class="role === 'patient' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                        <input type="radio" name="role" value="patient" x-model="role" class="hidden">
                        <i class="fas fa-user text-blue-500"></i>
                        <span class="font-medium">Patient</span>
                    </label>
                    <label class="flex items-center gap-3 border-2 rounded-lg p-3 cursor-pointer transition"
                           :class="role === 'doctor' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                        <input type="radio" name="role" value="doctor" x-model="role" class="hidden">
                        <i class="fas fa-user-md text-blue-500"></i>
                        <span class="font-medium">Médecin</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            
            <div x-show="role === 'patient'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            
            <div x-show="role === 'doctor'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biographie</label>
                    <textarea name="bio" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('bio') }}</textarea>
                </div>
                <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded-lg">
                    <i class="fas fa-info-circle mr-1"></i>
                    Votre compte devra être validé par un administrateur avant d'être visible.
                </p>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Se connecter</a>
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush