@extends('layouts.app')
@section('title', 'Dashboard mahasiswa')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-9 rounded-3xl bg-slate-950 px-7 py-9 sm:px-10">
        <p class="text-sm font-bold uppercase tracking-wider text-indigo-300">Ruang mahasiswa</p>
        <h1 class="mt-3 text-3xl font-extrabold text-white sm:text-4xl">Halo, {{ auth()->user()->name }}</h1>
        <p class="mt-3 max-w-2xl text-slate-300">Temukan peluang studi, simpan beasiswa pilihan, dan pantau perkembangan pendaftaran Anda.</p>
    </div>
    <div class="grid gap-5 md:grid-cols-3">
        <a class="card p-6 transition hover:-translate-y-0.5 hover:shadow-md" href="{{ route('scholarships.index') }}">
            <span class="text-sm font-bold text-indigo-700">JELAJAHI</span><h2 class="mt-3 text-xl font-bold text-slate-950">Cari beasiswa</h2><p class="mt-2 text-sm leading-6 text-slate-500">Temukan program studi dan pendanaan yang sesuai.</p>
        </a>
        <a class="card p-6 transition hover:-translate-y-0.5 hover:shadow-md" href="{{ route('applications.index') }}">
            <span class="text-sm font-bold text-indigo-700">PENDAFTARAN</span><h2 class="mt-3 text-xl font-bold text-slate-950">Pantau lamaran</h2><p class="mt-2 text-sm leading-6 text-slate-500">Lihat status dan riwayat pendaftaran Anda.</p>
        </a>
        <a class="card p-6 transition hover:-translate-y-0.5 hover:shadow-md" href="{{ route('profile.edit') }}">
            <span class="text-sm font-bold text-indigo-700">AKUN ANDA</span><h2 class="mt-3 text-xl font-bold text-slate-950">Lengkapi profil</h2><p class="mt-2 text-sm leading-6 text-slate-500">Simpan IPK dan skor bahasa pada profil akademik.</p>
        </a>
    </div>
</section>
@endsection
