<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'YUHLEZ')</title>
    <meta name="description" content="@yield('description', 'YUHLEZ Software House - solusi website, web apps, dan sistem digital dari Tegal.')">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    {{-- Tailwind CSS v4 (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Trix Editor CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.19/dist/trix.min.css">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body class="min-h-screen bg-white font-sans antialiased">

    {{-- PUBLIC HEADER --}}
    <header class="sticky top-0 z-40 border-b border-zinc-800 bg-zinc-950/90 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="YUHLEZ Beranda">
                <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ Software House" height="30" class="h-[30px] w-auto" />
            </a>

            <nav class="hidden items-center gap-1 sm:flex" aria-label="Navigasi utama">
                @php
                    $navLinks = [
                        ['href' => '/', 'label' => 'Beranda'],
                        ['href' => '/magang', 'label' => 'Program Magang'],
                        ['href' => '/perusahaan', 'label' => 'Perusahaan'],
                        ['href' => '/karya', 'label' => 'Karya'],
                    ];
                @endphp
                @foreach($navLinks as $link)
                @php
                    $path = request()->path();
                    $linkPath = ltrim($link['href'], '/');
                    $isActive = $link['href'] === '/'
                        ? $path === ''
                        : (str_starts_with($path, $linkPath));
                @endphp
                    <a href="{{ $link['href'] }}" class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $isActive ? 'text-yellow-400' : 'text-zinc-300 hover:bg-zinc-900 hover:text-white' }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <button id="darkToggle" class="w-10 h-10 rounded-lg flex items-center justify-center text-zinc-300 hover:bg-zinc-800 transition-colors" title="Toggle dark mode">
                    <svg id="sunIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="moonIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                @auth
                    <a href="{{ match(Auth::user()->role) {
                        'ROOT' => route('root.dashboard'),
                        'COMPANY' => route('company.dashboard'),
                        'INTERN' => route('intern.dashboard'),
                        default => route('home'),
                    } }}" class="rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-zinc-950 transition-colors hover:bg-yellow-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-yellow-300 hover:shadow-lg hover:shadow-yellow-400/20 transition-all duration-200">Masuk</a>
                    <a href="{{ route('register.choice') }}" class="rounded-lg border border-zinc-500/50 px-4 py-2 text-sm font-semibold text-zinc-100 hover:bg-white/10 hover:border-zinc-400 transition-all duration-200">Daftar</a>
                @endauth

                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="sm:hidden rounded-lg p-2 text-zinc-300 hover:bg-zinc-900 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden sm:hidden border-t border-zinc-800 bg-zinc-950">
            <div class="px-4 py-3 space-y-1">
                @foreach($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-zinc-300 hover:bg-zinc-900 hover:text-white">{{ $link['label'] }}</a>
                @endforeach

            </div>
        </div>
    </header>

    <main class="flex-1">@yield('body')</main>

    {{-- PUBLIC FOOTER --}}
    <footer class="border-t border-zinc-800 bg-zinc-950">
        <div class="mx-auto w-full max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div class="space-y-3">
                    <img src="{{ asset('brand/yuhlez-logo.png') }}" alt="YUHLEZ" height="28" class="h-7 w-auto" />
                    <p class="text-sm leading-relaxed text-zinc-400">From Useless to YUHLEZ. Software house berbasis di Tegal, Jawa Tengah.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Navigasi</h3>
                    <ul class="mt-3 space-y-2 text-sm text-zinc-400">
                        <li><a href="/" class="hover:text-yellow-400">Beranda</a></li>
                        <li><a href="/magang" class="hover:text-yellow-400">Program Magang</a></li>
                        <li><a href="/perusahaan" class="hover:text-yellow-400">Perusahaan</a></li>
                        <li><a href="/karya" class="hover:text-yellow-400">Karya</a></li>
                        <li><a href="/login" class="hover:text-yellow-400">Masuk</a></li>
                        <li><a href="/register" class="hover:text-yellow-400">Daftar</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Kontak</h3>
                    <ul class="mt-3 space-y-2 text-sm text-zinc-400">
                        <li>Email: <a href="mailto:admin@yuhlez.com" class="hover:text-yellow-400">admin@yuhlez.com</a></li>
                        <li>WhatsApp: +62 821-2512-6584</li>
                        <li>Website: <a href="https://yuhlez.com" target="_blank" rel="noopener noreferrer" class="hover:text-yellow-400">yuhlez.com</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Lokasi</h3>
                    <p class="mt-3 text-sm leading-relaxed text-zinc-400">
                        Kantor pusat: Tegal, Jawa Tengah<br>
                        Lokasi utama: Kalisapu, Slawi, Tegal
                    </p>
                </div>
            </div>
            <div class="mt-10 border-t border-zinc-800 pt-6 text-xs text-zinc-500">
                <p>&copy; {{ date('Y') }} CV Talang Digital Indonesia (YUHLEZ Software House). Platform magang YUHLEZ.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
    {{-- Trix Editor JS --}}
    <script src="https://unpkg.com/trix@2.1.19/dist/trix.min.js"></script>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios@1.11.0/dist/axios.min.js"></script>
    {{-- App JS --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    (function() {
        const toggle = document.getElementById('darkToggle');
        const sun = document.getElementById('sunIcon');
        const moon = document.getElementById('moonIcon');
        const html = document.documentElement;

        function setDark(on) {
            if (on) {
                html.classList.add('dark');
                sun.classList.remove('hidden');
                moon.classList.add('hidden');
                localStorage.setItem('yuhlez-dark', '1');
            } else {
                html.classList.remove('dark');
                sun.classList.add('hidden');
                moon.classList.remove('hidden');
                localStorage.setItem('yuhlez-dark', '0');
            }
        }
        const saved = localStorage.getItem('yuhlez-dark');
        setDark(saved === '1' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches));
        toggle.addEventListener('click', () => setDark(!html.classList.contains('dark')));
    })();
    </script>
</body>
</html>
