<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BeasiswaHub') · BeasiswaHub</title>
    <meta name="description" content="Temukan, simpan, dan daftar beasiswa dalam satu platform informasi beasiswa terintegrasi.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8" aria-label="Navigasi utama">
            <a href="{{ route('scholarships.index') }}" class="flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-xl bg-indigo-600 text-lg font-black text-white">B</span>
                <span class="text-lg font-extrabold tracking-tight text-slate-950">Beasiswa<span class="text-indigo-600">Hub</span></span>
            </a>
            <div class="hidden items-center gap-7 md:flex">
                <a class="nav-link" href="{{ route('scholarships.index') }}">Cari beasiswa</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard admin</a>
                        <a class="nav-link" href="{{ route('admin.scholarships.index') }}">Kelola beasiswa</a>
                    @else
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('applications.index') }}">Lamaran saya</a>
                        <a class="nav-link" href="{{ route('bookmarks.index') }}">Simpanan</a>
                        <a class="nav-link" href="{{ route('profile.edit') }}">Profil</a>
                    @endif
                @endauth
            </div>
            <div class="flex items-center gap-3">
                @guest
                    <a class="button-secondary hidden sm:inline-flex" href="{{ route('login') }}">Masuk</a>
                    <a class="button-primary" href="{{ route('register') }}">Daftar gratis</a>
                @else
                    <span class="hidden text-sm font-medium text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="button-secondary" type="submit">Keluar</button></form>
                @endguest
            </div>
        </nav>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-5 max-w-7xl px-5 lg:px-8">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('status') }}</div>
        </div>
    @endif

    <main>@yield('content')</main>
    <footer class="mt-20 border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-8 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p>© {{ now()->year }} BeasiswaHub · Temukan kesempatan, wujudkan masa depan.</p>
            <a class="font-medium text-indigo-700 hover:text-indigo-900" href="{{ route('scholarships.index') }}">Jelajahi beasiswa</a>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
