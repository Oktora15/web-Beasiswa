@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<section class="mx-auto max-w-lg px-5 py-14">
    <div class="card p-7 sm:p-9">
        <p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Selamat datang kembali</p>
        <h1 class="mt-2 text-3xl font-extrabold text-slate-950">Masuk ke akun</h1>
        <p class="mt-2 text-sm text-slate-500">Lanjutkan mencari peluang beasiswa terbaik untuk Anda.</p>
        <form class="mt-7 space-y-5" method="POST" action="{{ route('login') }}">
            @csrf
            <label class="block text-sm font-semibold">Email
                <input class="field mt-1" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-semibold">Kata sandi
                <input class="field mt-1" type="password" name="password" autocomplete="current-password" required>
                @error('password')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-indigo-600"> Ingat saya</label>
            <div class="flex items-center justify-between gap-3">
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-700" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
                <button class="button-primary" type="submit">Masuk</button>
            </div>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">Belum punya akun? <a class="font-semibold text-indigo-700" href="{{ route('register') }}">Daftar sekarang</a></p>
    </div>
</section>
@endsection
