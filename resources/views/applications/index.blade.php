@extends('layouts.app')
@section('title', 'Lamaran saya')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Ruang mahasiswa</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Lamaran saya</h1><p class="mt-2 text-slate-500">Pantau perkembangan setiap pendaftaran beasiswa.</p></div><a class="button-primary" href="{{ route('profile.edit') }}">Lengkapi profil</a></div>
    <div class="card overflow-hidden">
        @forelse ($applications as $application)
            <article class="flex flex-col gap-4 border-b border-slate-100 p-6 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">{{ $application->scholarship->country }} · {{ $application->scholarship->degree_level }}</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-950">{{ $application->scholarship->title }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Dikirim {{ $application->submitted_at?->translatedFormat('d M Y') ?? 'belum dikirim' }} · {{ $application->documents->count() }} dokumen</p>
                    <a class="mt-2 inline-flex text-sm font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('applications.show', $application) }}">Lihat lamaran →</a>
                </div>
                <span class="rounded-full px-3 py-1.5 text-sm font-bold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
            </article>
        @empty
            <div class="p-12 text-center"><h2 class="font-bold text-slate-900">Belum ada lamaran</h2><p class="mt-2 text-sm text-slate-500">Simpan beasiswa yang menarik dan mulai mendaftar.</p><a class="button-primary mt-5" href="{{ route('scholarships.index') }}">Cari beasiswa</a></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</section>
@endsection
