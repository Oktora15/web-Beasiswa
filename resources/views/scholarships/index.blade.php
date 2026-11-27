@extends('layouts.app')

@section('title', 'Cari beasiswa')

@section('content')
    <section class="relative overflow-hidden bg-slate-950">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-700/50 via-slate-950 to-slate-950"></div>
        <div class="relative mx-auto max-w-7xl px-5 py-16 sm:py-20 lg:px-8">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-300/25 bg-indigo-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[.15em] text-indigo-200">
                    <span class="size-1.5 rounded-full bg-emerald-400"></span> Peluang pendidikan global
                </span>
                <h1 class="mt-6 text-4xl font-black tracking-tight text-white sm:text-6xl">Beasiswa yang tepat,<br><span class="text-indigo-300">masa depan yang luas.</span></h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">Temukan peluang studi S1, S2, dan S3 dari berbagai negara. Bandingkan persyaratan dan pantau pendaftaran Anda di satu tempat.</p>
                <div class="mt-8 flex flex-wrap gap-3 text-sm text-slate-300">
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Informasi terkurasi</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Pengingat deadline</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Pantau status lamaran</span>
                </div>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Temukan peluang</p><h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Jelajahi beasiswa</h2></div>
            <p class="text-sm text-slate-500">Gunakan filter untuk menemukan beasiswa yang sesuai.</p>
        </div>
        <livewire:scholarship-filters />
    </section>
@endsection
