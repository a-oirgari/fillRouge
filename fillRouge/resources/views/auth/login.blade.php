@extends('layouts.app')
@section('title', 'Connexion')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-heartbeat text-blue-600 text-4xl mb-3"></i>
            <h1 class="text-2xl font-bold text-gray-800">Bienvenue sur MediConnect</h1>
            <p class="text-gray-500 text-sm mt-1">Connectez-vous à votre compte</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                       placeholder="exemple@email.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded">
                    Se souvenir de moi
                </label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">S'inscrire</a>
        </p>
    </div>
</div>
@endsection