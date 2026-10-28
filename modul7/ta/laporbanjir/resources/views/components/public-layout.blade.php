<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaporBanjir') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-600 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 15c1.5 0 1.5-1.5 3-1.5s1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5M3 19c1.5 0 1.5-1.5 3-1.5s1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5M12 3v6m0 0 2.5-2.5M12 9 9.5 6.5" />
                        </svg>
                    </span>
                    <span class="text-lg font-bold tracking-tight text-slate-900">LaporBanjir</span>
                </a>

                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 sm:flex">
                    <a href="{{ route('home') }}" class="hover:text-sky-700">Beranda</a>
                    <a href="{{ route('laporan.create') }}" class="hover:text-sky-700">Lapor Banjir</a>
                    @auth
                        <a href="{{ route('laporan.index') }}" class="hover:text-sky-700">Kelola Laporan</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-sky-700">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-sky-700">Masuk Petugas</a>
                    @endauth
                </nav>

                <a href="{{ route('laporan.create') }}" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 sm:hidden">
                    Lapor
                </a>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="mt-16 border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} LaporBanjir &middot; BPBD Kabupaten Bandung</p>
                <p>Laporan warga membantu petugas menentukan prioritas penanganan.</p>
            </div>
        </footer>
    </body>
</html>
