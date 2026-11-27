@extends('layouts.app')
@section('title', 'Review lamaran')
@section('content')
<section class="mx-auto max-w-4xl px-5 py-12 lg:px-8">
    <a class="text-sm font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('admin.applications.index') }}">← Kembali ke daftar lamaran</a>
    <article class="card mt-6 space-y-7 p-6 sm:p-8">
        <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-700">Review lamaran</p>
                <h1 class="mt-2 text-2xl font-extrabold text-slate-950">{{ $application->scholarship->title }}</h1>
                <p class="mt-2 text-sm text-slate-500">Dikirim {{ $application->submitted_at?->translatedFormat('d M Y H:i') ?? 'belum dikirim' }}</p>
            </div>
            <span class="inline-flex w-fit rounded-full px-3 py-1.5 text-sm font-bold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
        </header>

        <section class="border-t border-slate-100 pt-5">
            <h2 class="font-bold text-slate-900">Informasi mahasiswa</h2>
            <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-slate-500">Nama</dt><dd class="font-semibold text-slate-900">{{ $application->user->name }}</dd></div>
                <div><dt class="text-slate-500">Email</dt><dd class="font-semibold text-slate-900">{{ $application->user->email }}</dd></div>
                @if ($application->user->profile)
                    <div><dt class="text-slate-500">IPK</dt><dd class="font-semibold text-slate-900">{{ $application->user->profile->gpa ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Negara asal</dt><dd class="font-semibold text-slate-900">{{ $application->user->profile->country_of_origin ?? '—' }}</dd></div>
                @endif
            </dl>
        </section>

        <section class="border-t border-slate-100 pt-5">
            <h2 class="font-bold text-slate-900">Catatan motivasi</h2>
            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $application->motivation ?: 'Tidak ada catatan motivasi.' }}</p>
        </section>

        <section class="border-t border-slate-100 pt-5">
            <h2 class="font-bold text-slate-900">Dokumen lamaran</h2>
            <ul class="mt-3 divide-y divide-slate-100">
                @forelse ($application->documents as $document)
                    @php($documentLabel = ['cv' => 'CV', 'transcript' => 'Transkrip', 'motivation_letter' => 'Surat motivasi', 'language_certificate' => 'Sertifikat bahasa'][$document->document_type] ?? $document->document_type)
                    <li class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-0">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $documentLabel }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $document->original_name }} · {{ number_format($document->size / 1024, 1) }} KB</p>
                        </div>
                        <a class="text-sm font-semibold text-indigo-700 underline" href="{{ route('admin.documents.download', $document) }}">Unduh dokumen</a>
                    </li>
                @empty
                    <li class="py-3 text-sm text-slate-500">Belum ada dokumen tersimpan.</li>
                @endforelse
            </ul>
        </section>

        <section class="border-t border-slate-100 pt-5">
            <h2 class="font-bold text-slate-900">Perbarui status</h2>
            @if ($application->status->adminTransitions() !== [])
                <form class="mt-3 flex flex-col gap-3 sm:flex-row" method="POST" action="{{ route('admin.applications.update', $application) }}">
                    @csrf
                    @method('PATCH')
                    <label class="sr-only" for="status">Status lamaran</label>
                    <select class="field sm:max-w-xs" id="status" name="status" required>
                        <option value="" selected disabled>Pilih status</option>
                        @foreach ($application->status->adminTransitions() as $nextStatus)
                            <option value="{{ $nextStatus->value }}">{{ $nextStatus->label() }}</option>
                        @endforeach
                    </select>
                    <button class="button-primary" type="submit">Simpan status</button>
                </form>
                @error('status')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
            @else
                <p class="mt-2 text-sm text-slate-500">Status ini tidak dapat diubah lagi.</p>
            @endif
        </section>
    </article>
</section>
@endsection
