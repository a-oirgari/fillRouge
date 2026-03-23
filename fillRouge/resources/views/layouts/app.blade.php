<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect - @yield('title', 'Plateforme de téléconsultation')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">


    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <i class="fas fa-heartbeat text-blue-600 text-2xl"></i>
                    <span class="text-xl font-bold text-blue-600">MediConnect</span>
                </a>

                <div class="flex items-center gap-6">
                    <a href="{{ route('doctors.search') }}" class="text-gray-600 hover:text-blue-600 font-medium">
                        <i class="fas fa-search mr-1"></i> Médecins
                    </a>

                    @auth
                        @if(auth()->user()->isPatient())
                            <a href="{{ route('patient.dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                            <a href="{{ route('patient.appointments') }}" class="text-gray-600 hover:text-blue-600">Mes RDV</a>
                        @elseif(auth()->user()->isDoctor())
                            <a href="{{ route('doctor.dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                            <a href="{{ route('doctor.appointments') }}"
                                class="text-gray-600 hover:text-blue-600">Rendez-vous</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600">Admin</a>
                        @endif

                        <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-envelope"></i>
                        </a>

                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-sm hover:bg-red-100 transition">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium">Connexion</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-4 mt-4 rounded" x-data
            x-init="setTimeout(() => $el.remove(), 4000)">
            <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 mt-4 rounded">
            @foreach($errors->all() as $error)
                <p class="text-red-700 text-sm"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>


    <footer class="bg-white border-t border-gray-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} MediConnect — Plateforme de téléconsultation médicale</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>