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
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="card p-6">
            <h2 class="text-xl font-bold text-slate-950">Lamaran menurut status</h2>
            <div class="mt-5 space-y-4">
                @foreach ($applicationStatuses as $status)
                    <div>
                        <div class="mb-1 flex justify-between gap-4 text-sm"><span class="font-medium text-slate-700">{{ $status['label'] }}</span><span class="font-bold text-slate-950">{{ $status['count'] }}</span></div>
                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100" role="img" aria-label="{{ $status['label'] }}: {{ $status['count'] }} lamaran">
                            <div class="h-full rounded-full bg-indigo-600" style="width: {{ $status['bar_width'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="card p-6">
            <h2 class="text-xl font-bold text-slate-950">Beasiswa paling banyak dilamar</h2>
            <ol class="mt-5 divide-y divide-slate-100">
                @forelse ($mostAppliedScholarships as $scholarship)
                    <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <a class="font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('scholarships.show', $scholarship) }}">{{ $scholarship->title }}</a>
                        <span class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-sm font-bold text-indigo-800">{{ $scholarship->applications_count }} lamaran</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-slate-500">Belum ada beasiswa yang dilamar.</li>
                @endforelse
            </ol>
        </section>
    </div>
    <div class="mb-4 mt-10 flex items-center justify-between"><h2 class="text-xl font-bold text-slate-950">Lamaran terbaru</h2><a class="font-semibold text-indigo-700" href="{{ route('admin.applications.index') }}">Semua lamaran →</a></div>
    <div class="card overflow-x-auto">
        <table class="w-full min-w-[700px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4">Mahasiswa</th><th class="px-5 py-4">Beasiswa</th><th class="px-5 py-4">Dikirim</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($applications as $application)
                    <tr><td class="px-5 py-4 font-semibold text-slate-900">{{ $application->user->name }}<span class="mt-1 block font-normal text-slate-500">{{ $application->user->email }}</span></td><td class="px-5 py-4">{{ $application->scholarship->title }}</td><td class="px-5 py-4">{{ $application->submitted_at?->translatedFormat('d M Y') ?? '—' }}</td><td class="px-5 py-4">{{ $application->status->label() }}</td><td class="px-5 py-4"><a class="font-semibold text-indigo-700" href="{{ route('admin.applications.show', $application) }}">Review</a></td></tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada lamaran mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex gap-3"><a class="button-primary" href="{{ route('admin.scholarships.index') }}">Kelola beasiswa</a><a class="button-secondary" href="{{ route('admin.applications.index') }}">Tinjau lamaran</a></div>
</section>
@endsection
