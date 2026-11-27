@extends('layouts.app')
@section('title', 'Dashboard admin')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Panel administrator</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Ringkasan platform</h1><p class="mt-2 text-slate-500">Pantau peluang beasiswa dan aktivitas pendaftaran.</p></div>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['Total beasiswa', $stats['scholarships'], '✦'], ['Masih dibuka', $stats['open_scholarships'], '◷'], ['Total lamaran', $stats['applications'], '▤'], ['Perlu ditinjau', $stats['pending_applications'], '◉']] as [$label, $value, $icon])
            <article class="card p-5"><div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-500">{{ $label }}</p><span class="grid size-9 place-items-center rounded-xl bg-indigo-50 text-lg text-indigo-700">{{ $icon }}</span></div><p class="mt-4 text-3xl font-extrabold text-slate-950">{{ $value }}</p></article>
        @endforeach
    </div>
    <div class="mb-4 mt-10 flex items-center justify-between"><h2 class="text-xl font-bold text-slate-950">Lamaran terbaru</h2><a class="font-semibold text-indigo-700" href="{{ route('admin.applications.index') }}">Semua lamaran →</a></div>
    <div class="card overflow-x-auto">
        <table class="w-full min-w-[700px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4">Mahasiswa</th><th class="px-5 py-4">Beasiswa</th><th class="px-5 py-4">Dikirim</th><th class="px-5 py-4">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($applications as $application)
                    <tr><td class="px-5 py-4 font-semibold text-slate-900">{{ $application->user->name }}<span class="mt-1 block font-normal text-slate-500">{{ $application->user->email }}</span></td><td class="px-5 py-4">{{ $application->scholarship->title }}</td><td class="px-5 py-4">{{ $application->submitted_at?->translatedFormat('d M Y') ?? '—' }}</td><td class="px-5 py-4 capitalize">{{ $application->status }}</td></tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada lamaran mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex gap-3"><a class="button-primary" href="{{ route('admin.scholarships.index') }}">Kelola beasiswa</a><a class="button-secondary" href="{{ route('admin.applications.index') }}">Tinjau lamaran</a></div>
</section>
@endsection
