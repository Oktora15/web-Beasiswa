@extends('layouts.app')
@section('title', 'Tinjau lamaran')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Panel administrator</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Tinjau lamaran</h1><p class="mt-2 text-slate-500">Perbarui tahap seleksi dan hasil pendaftaran.</p></div>
    <div class="space-y-4">
        @forelse ($applications as $application)
            <article class="card flex flex-col gap-5 p-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0"><h2 class="font-bold text-slate-950">{{ $application->user->name }} <span class="font-normal text-slate-500">· {{ $application->user->email }}</span></h2><p class="mt-1 text-sm text-indigo-700">{{ $application->scholarship->title }}</p><p class="mt-2 max-w-2xl text-sm text-slate-600">{{ $application->motivation }}</p><p class="mt-2 text-xs text-slate-500">{{ $application->documents->count() }} dokumen · {{ $application->submitted_at?->translatedFormat('d M Y H:i') }}</p><div class="mt-2 flex flex-wrap gap-3">@foreach($application->documents as $document)<a class="text-xs font-semibold text-indigo-700 underline" href="{{ route('admin.documents.download', $document) }}">{{ $document->original_name }}</a>@endforeach</div></div>
                <form class="flex shrink-0 items-center gap-2" method="POST" action="{{ route('admin.applications.update', $application) }}">@csrf @method('PATCH')<select class="field" name="status" aria-label="Status lamaran">@foreach(['submitted'=>'Terkirim','review'=>'Sedang ditinjau','accepted'=>'Diterima','rejected'=>'Ditolak'] as $value=>$label)<option value="{{ $value }}" @selected($application->status === $value)>{{ $label }}</option>@endforeach</select><button class="button-primary" type="submit">Perbarui</button></form>
            </article>
        @empty
            <div class="card p-12 text-center text-slate-500">Belum ada lamaran yang masuk.</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</section>
@endsection
