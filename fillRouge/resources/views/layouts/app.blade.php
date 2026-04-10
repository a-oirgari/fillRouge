<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect - @yield('title', 'Plateforme de téléconsultation')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">


    <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600 text-white shadow-sm">
                        <i class="fas fa-heartbeat text-lg"></i>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-primary-700">MediConnect</span>
                </a>

                <div class="flex items-center gap-4 md:gap-6">
                    <a href="{{ route('doctors.search') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">
                        <i class="fas fa-search mr-1 text-primary-600/80"></i> Médecins
                    </a>

                    @auth
                        @if(auth()->user()->isPatient())
                            <a href="{{ route('patient.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Dashboard</a>
                            <a href="{{ route('patient.appointments') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Mes RDV</a>
                        @elseif(auth()->user()->isDoctor())
                            <a href="{{ route('doctor.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Dashboard</a>
                            <a href="{{ route('doctor.appointments') }}"
                                class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Rendez-vous</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Admin</a>
                        @endif

                        <a href="{{ route('messages.index') }}" id="nav-messages-link"
                           class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-primary-700"
                           title="Messages">
                            <i class="fas fa-envelope text-lg"></i>
                            <span id="nav-msg-badge" class="absolute right-1 top-1 hidden min-h-[1.125rem] min-w-[1.125rem] rounded-full bg-primary-600 px-1 text-center text-[0.65rem] font-bold leading-tight text-white">0</span>
                        </a>

                        <div class="flex items-center gap-3 pl-1 border-l border-slate-200">
                            <span class="hidden sm:inline text-sm font-medium text-slate-700 max-w-[10rem] truncate">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                                    <i class="fas fa-sign-out-alt text-slate-500"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-primary-700 transition">Connexion</a>
                        <a href="{{ route('register') }}"
                            class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mx-4 mt-4 rounded" x-data
            x-init="setTimeout(() => $el.remove(), 4000)">
            <p class="text-emerald-800"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
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


    <footer class="mt-16 border-t border-slate-200 bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} MediConnect — Téléconsultation médicale</p>
        </div>
    </footer>

    @auth
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        (function () {
            const key = @json(config('reverb.apps.apps.0.key'));
            const navBadge = document.getElementById('nav-msg-badge');
            if (!navBadge) return;

            function syncBadgeFromServer() {
                fetch('{{ route('messages.unread-count') }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var n = parseInt(data.count, 10) || 0;
                        if (n > 0) {
                            navBadge.textContent = n > 99 ? '99+' : String(n);
                            navBadge.classList.remove('hidden');
                        } else {
                            navBadge.classList.add('hidden');
                        }
                    })
                    .catch(function () {});
            }

            syncBadgeFromServer();

            if (!key) return;

            const pusher = new Pusher(key, {
                wsHost: @json(config('reverb.apps.apps.0.options.host', env('REVERB_HOST', 'localhost'))),
                wsPort: {{ (int) config('reverb.apps.apps.0.options.port', env('REVERB_PORT', 8080)) }},
                wssPort: {{ (int) config('reverb.apps.apps.0.options.port', env('REVERB_PORT', 8080)) }},
                forceTLS: false,
                enabledTransports: ['ws', 'wss'],
                cluster: 'mt1',
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }
            });
            window.MediConnectPusher = pusher;

            const userChannel = 'private-user.' + {{ auth()->id() }};
            const ch = pusher.subscribe(userChannel);
            ch.bind('message.sent', function (data) {
                var pathMatch = window.location.pathname.match(/^\/messages\/(\d+)$/);
                var openId = pathMatch ? parseInt(pathMatch[1], 10) : null;
                if (openId && data.sender_id === openId) {
                    return;
                }
                var n = parseInt(navBadge.textContent, 10) || 0;
                if (navBadge.classList.contains('hidden')) {
                    navBadge.textContent = '1';
                    navBadge.classList.remove('hidden');
                } else {
                    n += 1;
                    navBadge.textContent = n > 99 ? '99+' : String(n);
                }
            });
        })();
    </script>
    @endauth

    @stack('scripts')
</body>

</html>
