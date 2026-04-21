<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect — @yield('title', __('app.meta.default_title'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        arabic: ['"Tajawal"', '"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        surface: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                        },
                    },
                    backgroundImage: {
                        'page-gradient': 'linear-gradient(165deg, #e8f0fe 0%, #f1f5f9 38%, #eef2ff 100%)',
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
</head>

<body class="min-h-screen bg-page-gradient font-sans text-slate-800 antialiased flex flex-col {{ app()->getLocale() === 'ar' ? 'font-arabic' : '' }}">


    <nav x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/85 shadow-sm backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-800 text-white shadow-md">
                        <i class="fas fa-heartbeat text-lg"></i>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-slate-900">MediConnect</span>
                </a>

                <!-- Hamburger Button (Mobile) -->
                <div class="flex md:hidden items-center gap-3 relative">
                    @auth
                    <a href="{{ route('messages.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-primary-700">
                        <i class="fas fa-envelope text-lg"></i>
                        <span id="nav-msg-badge-mobile" class="absolute end-1 top-1 hidden min-h-[1.125rem] min-w-[1.125rem] rounded-full bg-primary-600 px-1 text-center text-[0.65rem] font-bold leading-tight text-white">0</span>
                    </a>
                    @endauth
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-primary-700 focus:outline-none p-2 relative z-10 rounded-lg bg-surface-50 border border-slate-200">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex flex-wrap items-center justify-end gap-2 sm:gap-4 md:gap-5">
                    {{-- Sélecteur de langue --}}
                    <div class="flex items-center rounded-lg border border-slate-200/90 bg-surface-50 p-0.5 text-xs font-semibold shadow-sm">
                        <a href="{{ route('locale.switch', ['locale' => 'fr']) }}"
                           class="rounded-md px-2.5 py-1.5 transition {{ app()->getLocale() === 'fr' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">FR</a>
                        <a href="{{ route('locale.switch', ['locale' => 'ar']) }}"
                           class="rounded-md px-2.5 py-1.5 transition {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">عربي</a>
                    </div>

                    <a href="{{ route('doctors.search') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fas fa-search text-primary-600/90"></i> {{ __('app.nav.doctors') }}
                        </span>
                    </a>

                    @auth
                        @if(auth()->user()->isPatient())
                            <a href="{{ route('patient.dashboard') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.dashboard') }}</a>
                            <a href="{{ route('patient.appointments') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.my_appointments') }}</a>
                        @elseif(auth()->user()->isDoctor())
                            <a href="{{ route('doctor.dashboard') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.dashboard') }}</a>
                            <a href="{{ route('doctor.appointments') }}"
                                class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.appointments') }}</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.admin') }}</a>
                        @endif

                        <a href="{{ route('messages.index') }}" id="nav-messages-link"
                           class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-primary-700"
                           title="{{ __('app.nav.messages') }}">
                            <i class="fas fa-envelope text-lg"></i>
                            <span id="nav-msg-badge" class="absolute end-1 top-1 hidden min-h-[1.125rem] min-w-[1.125rem] rounded-full bg-primary-600 px-1 text-center text-[0.65rem] font-bold leading-tight text-white">0</span>
                        </a>

                        <div class="flex items-center gap-2 border-s border-slate-200 ps-2 sm:ps-3">
                            <span class="hidden max-w-[10rem] truncate text-sm font-medium text-slate-700 sm:inline">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                                    <i class="fas fa-sign-out-alt text-slate-500"></i> {{ __('app.nav.logout') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 transition hover:text-primary-700">{{ __('app.nav.login') }}</a>
                        <a href="{{ route('register') }}"
                            class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            {{ __('app.nav.register') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden border-t border-slate-100 bg-white/95 backdrop-blur-lg absolute w-full shadow-lg">
            <div class="px-4 pt-4 pb-6 space-y-3 flex flex-col">
                {{-- Sélecteur de langue Mobile --}}
                <div class="flex items-center rounded-lg border border-slate-200/90 bg-surface-50 p-1 text-sm font-semibold shadow-sm max-w-[200px] mb-2">
                    <a href="{{ route('locale.switch', ['locale' => 'fr']) }}" class="flex-1 text-center rounded-md px-3 py-2 transition {{ app()->getLocale() === 'fr' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">FR</a>
                    <a href="{{ route('locale.switch', ['locale' => 'ar']) }}" class="flex-1 text-center rounded-md px-3 py-2 transition {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">عربي</a>
                </div>

                <a href="{{ route('doctors.search') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition">
                    <i class="fas fa-search text-primary-500 w-6"></i> {{ __('app.nav.doctors') }}
                </a>

                @auth
                    @if(auth()->user()->isPatient())
                        <a href="{{ route('patient.dashboard') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition"><i class="fas fa-chart-line text-primary-500 w-6"></i> {{ __('app.nav.dashboard') }}</a>
                        <a href="{{ route('patient.appointments') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition"><i class="fas fa-calendar-alt text-primary-500 w-6"></i> {{ __('app.nav.my_appointments') }}</a>
                    @elseif(auth()->user()->isDoctor())
                        <a href="{{ route('doctor.dashboard') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition"><i class="fas fa-chart-line text-primary-500 w-6"></i> {{ __('app.nav.dashboard') }}</a>
                        <a href="{{ route('doctor.appointments') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition"><i class="fas fa-calendar-alt text-primary-500 w-6"></i> {{ __('app.nav.appointments') }}</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block p-3 rounded-xl bg-slate-50 text-slate-700 font-medium hover:bg-primary-50 hover:text-primary-700 transition"><i class="fas fa-chart-pie text-primary-500 w-6"></i> {{ __('app.nav.admin') }}</a>
                    @endif
                    
                    <div class="h-px bg-slate-200 my-2"></div>
                    <div class="flex items-center justify-between p-3">
                        <span class="font-bold text-slate-800"><i class="fas fa-user-circle text-primary-500 mr-2"></i> {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-red-500 bg-red-50 px-4 py-2 rounded-lg hover:bg-red-100 transition">
                                <i class="fas fa-sign-out-alt"></i> {{ __('app.nav.logout') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="h-px bg-slate-200 my-2"></div>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <a href="{{ route('login') }}" class="text-center p-3 font-bold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition">{{ __('app.nav.login') }}</a>
                        <a href="{{ route('register') }}" class="text-center p-3 font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition">{{ __('app.nav.register') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>


    @if(session('success'))
        <div class="mx-4 mt-4 rounded border-s-4 border-emerald-400 bg-emerald-50/95 p-4 shadow-sm" x-data
            x-init="setTimeout(() => $el.remove(), 4000)">
            <p class="text-emerald-900"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-4 mt-4 rounded border-s-4 border-red-400 bg-red-50 p-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-800"><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif


    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 min-h-[100vh]">
        @yield('content')
    </main>


    <footer class="mt-auto border-t border-slate-200/90 bg-slate-900 py-10 text-slate-300">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm">
            <p>&copy; {{ date('Y') }} MediConnect — {{ __('app.footer.tagline') }}</p>
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

            // Listen for incoming calls
            ch.bind('App\\Events\\CallInitiated', function(data) {
                const callPopup = document.createElement('div');
                callPopup.className = "fixed bottom-5 right-5 z-[9999] bg-white rounded-xl shadow-2xl border-l-4 border-primary-500 p-5 w-80 transform transition-all duration-500 translate-y-full opacity-0";
                
                const rolePrefix = data.caller.role === 'doctor' ? 'Dr. ' : '';
                
                callPopup.innerHTML = `
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center animate-pulse">
                            <i class="fas fa-phone-volume text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Appel entrant</h4>
                            <p class="text-xs text-slate-500">${rolePrefix}${data.caller.name}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="/messages/${data.caller.id}/call?join=1" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg text-xs font-bold transition shadow-sm">
                            <i class="fas fa-video mr-1"></i> Répondre
                        </a>
                        <button onclick="this.closest('div.fixed').remove()" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 text-center py-2 rounded-lg text-xs font-bold transition">
                            Ignorer
                        </button>
                    </div>
                `;
                
                document.body.appendChild(callPopup);
                
                // Animate in
                setTimeout(() => {
                    callPopup.classList.remove('translate-y-full', 'opacity-0');
                }, 100);
            });
        })();
    </script>
    @endauth

    @stack('scripts')
</body>


</html>
