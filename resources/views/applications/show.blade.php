@extends('layouts.app')

@section('title', 'Detail lamaran')

@section('content')
    <section class="mx-auto max-w-4xl px-5 py-12 lg:px-8">
        <a class="text-sm font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('applications.index') }}">← Kembali ke lamaran saya</a>
        <article class="card mt-6 space-y-6 p-6 sm:p-8">
            <header>
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-700">{{ $application->scholarship->country }} · {{ $application->scholarship->degree_level }}</p>
                <h1 class="mt-2 text-2xl font-extrabold text-slate-950">{{ $application->scholarship->title }}</h1>
                <p class="mt-2 text-sm text-slate-500">Dikirim {{ $application->submitted_at?->translatedFormat('d M Y H:i') ?? 'belum dikirim' }}</p>
                <span class="mt-4 inline-flex rounded-full px-3 py-1.5 text-sm font-bold {{ $application->status->badgeClasses() }}">{{ $application->status->label() }}</span>
            </header>

            @if ($application->motivation)
                <section class="border-t border-slate-100 pt-5">
                    <h2 class="font-bold text-slate-900">Catatan motivasi</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $application->motivation }}</p>
                </section>
            @endif

            <section class="border-t border-slate-100 pt-5">
                <h2 class="font-bold text-slate-900">Dokumen lamaran</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    @forelse ($application->documents as $document)
                        @php($documentLabel = ['cv' => 'CV', 'transcript' => 'Transkrip', 'motivation_letter' => 'Surat motivasi', 'language_certificate' => 'Sertifikat bahasa'][$document->document_type] ?? $document->document_type)
                        <li class="flex flex-wrap justify-between gap-2">
                            <span>{{ $documentLabel }}</span>
                            <span>{{ $document->original_name }} · {{ Number::fileSize($document->size) }}</span>
                        </li>
                    @empty
                        <li>Belum ada dokumen tersimpan.</li>
                    @endforelse
                </ul>
            </section>

            @if ($application->status === \App\ApplicationStatus::Draft)
                <a class="button-primary justify-center" href="{{ route('scholarships.show', $application->scholarship) }}">Lanjutkan draf lamaran</a>
            @endif
        </article>
    </section>
@endsection
