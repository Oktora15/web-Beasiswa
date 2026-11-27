@extends('layouts.app')
@section('title', 'Tinjau lamaran')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Panel administrator</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Tinjau lamaran</h1><p class="mt-2 text-slate-500">Perbarui tahap seleksi dan hasil pendaftaran.</p></div>
    <form class="card mb-6 flex flex-col gap-3 p-4 sm:flex-row sm:items-end" method="GET" action="{{ route('admin.applications.index') }}">
        <label class="text-sm font-semibold text-slate-700" for="status">Filter status
            <select class="field mt-1 min-w-56" id="status" name="status">
                <option value="">Semua status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </label>
        <div class="flex gap-2"><button class="button-primary" type="submit">Terapkan</button><a class="button-secondary" href="{{ route('admin.applications.index') }}">Atur ulang</a></div>
    </form>
    <div class="space-y-4">
        @forelse ($applications as $application)
            <article class="card flex flex-col gap-5 p-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <h2 class="font-bold text-slate-950">{{ $application->user->name }} <span class="font-normal text-slate-500">· {{ $application->user->email }}</span></h2>
                    <p class="mt-1 text-sm text-indigo-700">{{ $application->scholarship->title }}</p>
                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
                    <p class="mt-2 max-w-2xl text-sm text-slate-600">{{ $application->motivation }}</p>
                    <p class="mt-2 text-xs text-slate-500">{{ $application->documents->count() }} dokumen · {{ $application->submitted_at?->translatedFormat('d M Y H:i') }}</p>
                    <a class="mt-3 inline-block text-sm font-semibold text-indigo-700 underline" href="{{ route('admin.applications.show', $application) }}">Buka halaman review</a>
                    <div class="mt-2 flex flex-wrap gap-3">
                        @foreach ($application->documents as $document)
                            @php($documentLabel = ['cv' => 'CV', 'transcript' => 'Transkrip', 'motivation_letter' => 'Surat motivasi', 'language_certificate' => 'Sertifikat bahasa'][$document->document_type] ?? $document->document_type)
                            <a class="text-xs font-semibold text-indigo-700 underline" href="{{ route('admin.documents.download', $document) }}">{{ $documentLabel }}: {{ $document->original_name }}</a>
                        @endforeach
                    </div>
                </div>
                @if ($application->status->adminTransitions() !== [])
                    <form class="flex shrink-0 items-center gap-2" method="POST" action="{{ route('admin.applications.update', $application) }}">
                        @csrf
                        @method('PATCH')
                        <select class="field" name="status" required aria-label="Status lamaran">
                            <option value="" selected disabled>Pilih status</option>
                            @foreach ($application->status->adminTransitions() as $nextStatus)
                                <option value="{{ $nextStatus->value }}">{{ $nextStatus->label() }}</option>
                            @endforeach
                        </select>
                        <button class="button-primary" type="submit">Perbarui</button>
                    </form>
                @else
                    <span class="rounded-full px-3 py-1.5 text-sm font-bold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
                @endif
            </article>
        @empty
            <div class="card p-12 text-center text-slate-500">{{ ($filters['status'] ?? null) ? 'Tidak ada lamaran dengan status tersebut.' : 'Belum ada lamaran yang masuk.' }}</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</section>
@endsection
