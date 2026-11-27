@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<section class="mx-auto max-w-lg px-5 py-14">
    <div class="card p-7 sm:p-9">
        <p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Mulai perjalanan Anda</p>
        <h1 class="mt-2 text-3xl font-extrabold text-slate-950">Buat akun mahasiswa</h1>
        <p class="mt-2 text-sm text-slate-500">Simpan beasiswa, kirim lamaran, dan pantau statusnya.</p>
        <form class="mt-7 space-y-5" method="POST" action="{{ route('register') }}">
            @csrf
            <label class="block text-sm font-semibold">Nama lengkap
                <input class="field mt-1" type="text" name="name" value="{{ old('name') }}" autocomplete="name" required>
                @error('name')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-semibold">Email
                <input class="field mt-1" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-semibold">Kata sandi
                <input class="field mt-1" type="password" name="password" autocomplete="new-password" required>
                @error('password')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="block text-sm font-semibold">Ulangi kata sandi
                <input class="field mt-1" type="password" name="password_confirmation" autocomplete="new-password" required>
            </label>
            <button class="button-primary w-full justify-center" type="submit">Buat akun</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-500">Sudah punya akun? <a class="font-semibold text-indigo-700" href="{{ route('login') }}">Masuk</a></p>
    </div>
</section>
@endsection
