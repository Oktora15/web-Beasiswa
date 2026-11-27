@extends('layouts.app')
@section('title', 'Beasiswa tersimpan')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8"><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Daftar pribadi</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Beasiswa tersimpan</h1><p class="mt-2 text-slate-500">Kami akan mengingatkan Anda tujuh hari dan satu hari sebelum deadline.</p></div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($bookmarks as $bookmark)
            <article class="card p-6">
                <div class="flex items-center justify-between"><span class="badge">{{ $bookmark->scholarship->country }}</span><span class="text-sm font-semibold text-rose-700">{{ $bookmark->scholarship->deadline->translatedFormat('d M Y') }}</span></div>
                <h2 class="mt-4 text-lg font-bold text-slate-950">{{ $bookmark->scholarship->title }}</h2><p class="mt-1 text-sm text-slate-500">{{ $bookmark->scholarship->provider }}</p>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><a class="font-semibold text-indigo-700" href="{{ route('scholarships.show', $bookmark->scholarship) }}">Lihat detail →</a><form method="POST" action="{{ route('bookmarks.toggle', $bookmark->scholarship) }}">@csrf<button class="text-sm font-medium text-slate-500 hover:text-rose-700" type="submit">Hapus</button></form></div>
            </article>
        @empty
            <div class="card p-12 text-center md:col-span-2 xl:col-span-3"><h2 class="font-bold">Belum ada beasiswa tersimpan</h2><a class="button-primary mt-5" href="{{ route('scholarships.index') }}">Cari beasiswa</a></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $bookmarks->links() }}</div>
</section>
@endsection
