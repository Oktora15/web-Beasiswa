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
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Temukan peluang</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Jelajahi beasiswa</h2>
            </div>
            <p class="text-sm text-slate-500">{{ $scholarships->total() }} beasiswa ditemukan</p>
        </div>
        <div class="grid items-start gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
            <aside class="card p-5 sm:p-6">
                <form method="GET" action="{{ route('scholarships.index') }}" class="space-y-5">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-bold text-slate-950">Filter beasiswa</h3>
                        <a class="text-sm font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('scholarships.index') }}">Atur ulang</a>
                    </div>

                    <label class="block text-sm font-semibold text-slate-700">
                        Kata kunci
                        <input class="field mt-1.5" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama atau penyedia">
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        Negara
                        <select class="field mt-1.5" name="country">
                            <option value="">Semua negara</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" @selected(($filters['country'] ?? '') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        Jenjang
                        <select class="field mt-1.5" name="degree_level">
                            <option value="">Semua jenjang</option>
                            @foreach (['S1', 'S2', 'S3'] as $degree)
                                <option value="{{ $degree }}" @selected(($filters['degree_level'] ?? '') === $degree)>{{ $degree }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        Jenis pendanaan
                        <select class="field mt-1.5" name="funding_type">
                            <option value="">Semua pendanaan</option>
                            <option value="fully" @selected(($filters['funding_type'] ?? '') === 'fully')>Fully funded</option>
                            <option value="partial" @selected(($filters['funding_type'] ?? '') === 'partial')>Parsial</option>
                        </select>
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        Kewajiban kembali
                        <select class="field mt-1.5" name="return_obligation">
                            <option value="">Semua program</option>
                            <option value="1" @selected(($filters['return_obligation'] ?? '') === '1')>Wajib kembali</option>
                            <option value="0" @selected(($filters['return_obligation'] ?? '') === '0')>Tidak wajib pulang</option>
                        </select>
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        IPK Anda
                        <input class="field mt-1.5" type="number" name="min_gpa" min="0" max="4" step="0.01" value="{{ $filters['min_gpa'] ?? '' }}" placeholder="Contoh: 3.25">
                    </label>

                    <label class="block text-sm font-semibold text-slate-700">
                        Usia Anda
                        <input class="field mt-1.5" type="number" name="max_age" min="16" max="100" step="1" value="{{ $filters['max_age'] ?? '' }}" placeholder="Dalam tahun">
                    </label>

                    <div class="space-y-3 border-t border-slate-100 pt-4">
                        <label class="flex items-start gap-2.5 text-sm font-medium text-slate-700">
                            <input class="mt-0.5 size-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" name="no_language_test" value="1" @checked(filter_var($filters['no_language_test'] ?? false, FILTER_VALIDATE_BOOLEAN))>
                            Tanpa IELTS/TOEFL
                        </label>
                        <label class="flex items-start gap-2.5 text-sm font-medium text-slate-700">
                            <input class="mt-0.5 size-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" name="open" value="1" @checked(filter_var($filters['open'] ?? false, FILTER_VALIDATE_BOOLEAN))>
                            Hanya yang masih dibuka
                        </label>
                    </div>

                    <button class="button-primary w-full justify-center" type="submit">Terapkan filter</button>
                </form>
            </aside>

            <div class="space-y-5">
                @forelse ($scholarships as $scholarship)
                    <article class="card flex flex-col gap-5 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="badge">{{ $scholarship->country }}</span>
                                <span class="badge">{{ $scholarship->degree_level }}</span>
                                @if ($scholarship->funding_type === 'fully')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Fully Funded</span>
                                @endif
                                @if ($scholarship->language_test === 'none')
                                    <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">Tanpa IELTS</span>
                                @endif
                                @unless ($scholarship->return_obligation)
                                    <span class="inline-flex items-center rounded-full bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700">Tidak Wajib Pulang</span>
                                @endunless
                            </div>
                            <h3 class="mt-4 text-xl font-bold leading-snug text-slate-950">{{ $scholarship->title }}</h3>
                            <p class="mt-1 text-sm font-medium text-slate-500">{{ $scholarship->provider }}</p>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $scholarship->description }}</p>
                            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600">
                                <span>{{ $scholarship->min_gpa ? 'IPK minimum '.$scholarship->min_gpa : 'IPK fleksibel' }}</span>
                                <span>{{ $scholarship->max_age ? 'Usia maks. '.$scholarship->max_age.' tahun' : 'Tanpa batas usia' }}</span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center justify-between gap-4 border-t border-slate-100 pt-4 sm:min-w-44 sm:flex-col sm:items-end sm:border-t-0 sm:pt-0">
                            <div class="sm:text-right">
                                <p class="text-xs font-medium text-slate-500">Batas pendaftaran</p>
                                <p class="mt-1 font-bold text-rose-700">{{ $scholarship->deadline->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a class="button-secondary" href="{{ route('scholarships.show', $scholarship) }}">Lihat detail</a>
                                @auth
                                    @if (auth()->user()->role === 'student')
                                        <form method="POST" action="{{ route('bookmarks.toggle', $scholarship) }}">
                                            @csrf
                                            <button class="button-secondary" type="submit" aria-label="{{ $scholarship->is_bookmarked > 0 ? 'Hapus dari simpanan' : 'Simpan beasiswa' }}">
                                                {{ $scholarship->is_bookmarked > 0 ? 'Tersimpan' : 'Simpan' }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a class="button-secondary" href="{{ route('login') }}" aria-label="Masuk untuk menyimpan beasiswa">Simpan</a>
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="card border-dashed p-10 text-center sm:p-14">
                        <p class="font-semibold text-slate-800">Beasiswa tidak ditemukan</p>
                        <p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci atau filter pencarian Anda.</p>
                    </div>
                @endforelse
                <div>{{ $scholarships->links() }}</div>
            </div>
        </div>
    </section>
@endsection
