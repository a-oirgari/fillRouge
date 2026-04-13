<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MediConnect — @yield('title', __('app.meta.default_title'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        arabic: ['"Noto Sans Arabic"', '"Plus Jakarta Sans"', 'sans-serif'],
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


    <nav class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/85 shadow-sm backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-800 text-white shadow-md">
                        <i class="fas fa-heartbeat text-lg"></i>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-slate-900">MediConnect</span>
                </a>

                <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-4 md:gap-5">
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
        })();
    </script>
    @endauth

    @stack('scripts')
</body>


</html>
