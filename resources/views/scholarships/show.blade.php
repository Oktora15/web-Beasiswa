@extends('layouts.app')

@section('title', $scholarship->title)

@section('content')
    <section class="bg-slate-950">
        <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
            <a href="{{ route('scholarships.index') }}" class="text-sm font-semibold text-indigo-200 hover:text-white">← Kembali ke daftar beasiswa</a>
            <div class="mt-7 flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-medium text-white">{{ $scholarship->country }}</span>
                <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-medium text-white">{{ $scholarship->degree_level }}</span>
                <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-medium text-white">{{ $scholarship->funding_type === 'fully' ? 'Pendanaan penuh' : 'Pendanaan parsial' }}</span>
            </div>
            <h1 class="mt-5 max-w-4xl text-3xl font-black tracking-tight text-white sm:text-5xl">{{ $scholarship->title }}</h1>
            <p class="mt-3 text-lg text-slate-300">{{ $scholarship->provider }}</p>
        </div>
    </section>
    <section class="mx-auto grid max-w-7xl gap-8 px-5 py-10 lg:grid-cols-[minmax(0,1fr)_360px] lg:px-8">
        <article class="card p-7 sm:p-9">
            <h2 class="text-xl font-bold text-slate-950">Tentang beasiswa</h2>
            <div class="prose prose-slate mt-4 max-w-none whitespace-pre-line leading-7 text-slate-600">{{ $scholarship->description }}</div>
            @if ($scholarship->criteria->isNotEmpty())
                <h2 class="mt-9 text-xl font-bold text-slate-950">Kriteria tambahan</h2>
                <ul class="mt-4 list-inside list-disc space-y-2 text-slate-600">
                    @foreach ($scholarship->criteria as $criterion)<li>{{ $criterion->name }}@if($criterion->description) — {{ $criterion->description }}@endif</li>@endforeach
                </ul>
            @endif
            <a class="mt-9 inline-flex font-semibold text-indigo-700 hover:text-indigo-900" href="{{ $scholarship->official_url }}" target="_blank" rel="noopener noreferrer">Kunjungi situs resmi ↗</a>
        </article>
        <aside class="space-y-5">
            <div class="card p-6">
                <h2 class="text-lg font-bold text-slate-950">Ringkasan persyaratan</h2>
                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Jenjang</dt><dd class="font-semibold">{{ $scholarship->degree_level }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Tes bahasa</dt><dd class="font-semibold">{{ str_replace('_', ' / ', strtoupper($scholarship->language_test)) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">IPK minimum</dt><dd class="font-semibold">{{ $scholarship->min_gpa ?? 'Tidak ditentukan' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Batas usia</dt><dd class="font-semibold">{{ $scholarship->max_age ? $scholarship->max_age.' tahun' : 'Tidak ditentukan' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Kembali ke negara asal</dt><dd class="font-semibold">{{ $scholarship->return_obligation ? 'Wajib' : 'Tidak wajib' }}</dd></div>
                    @if ($scholarship->return_duration_years)<div class="flex justify-between gap-4"><dt class="text-slate-500">Durasi kembali</dt><dd class="font-semibold">{{ $scholarship->return_duration_years }} tahun</dd></div>@endif
                    <div class="border-t border-slate-100 pt-4"><dt class="text-slate-500">Deadline</dt><dd class="mt-1 text-lg font-extrabold text-rose-700">{{ $scholarship->deadline->translatedFormat('d F Y') }}</dd></div>
                </dl>
                @auth
                    @if (!auth()->user()->isAdmin())
                        <form class="mt-5" method="POST" action="{{ route('bookmarks.toggle', $scholarship) }}">@csrf<button class="button-secondary w-full justify-center" type="submit">{{ $bookmarked ? 'Hapus dari simpanan' : 'Simpan beasiswa' }}</button></form>
                    @endif
                @else
                    <a class="button-secondary mt-5 w-full justify-center" href="{{ route('login') }}">Masuk untuk menyimpan</a>
                @endauth
            </div>
            @if ($eligibility !== null)
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-slate-950">Kecocokan profil Anda</h2>
                    <p class="mt-1 text-sm text-slate-500">Dibandingkan dengan IPK minimum dan batas usia program.</p>
                    <dl class="mt-4 space-y-3">
                        @foreach (['gpa' => 'IPK', 'age' => 'Batas usia'] as $criterion => $label)
                            @php
                                $status = $eligibility[$criterion];
                                $statusLabel = $status === null ? 'Lengkapi profil' : ($status ? 'Memenuhi' : 'Belum memenuhi');
                                $statusClass = $status === null
                                    ? 'bg-amber-50 text-amber-800'
                                    : ($status ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700');
                            @endphp
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <dt class="font-medium text-slate-600">{{ $label }}</dt>
                                <dd><span class="rounded-full px-3 py-1 font-semibold {{ $statusClass }}">{{ $statusLabel }}</span></dd>
                            </div>
                        @endforeach
                    </dl>
                    @if (in_array(null, $eligibility, true))
                        <a class="mt-4 inline-flex text-sm font-semibold text-indigo-700 hover:text-indigo-900" href="{{ route('profile.edit') }}">Lengkapi profil untuk hasil lebih akurat →</a>
                    @endif
                </div>
            @endif
            @auth
                @if (auth()->user()->role === 'student' && $scholarship->deadline->greaterThanOrEqualTo(today()))
                    @if ($application && $application->status->value !== 'draft')
                        <div class="card p-6"><h2 class="font-bold text-slate-950">Lamaran sudah dikirim</h2><p class="mt-2 text-sm text-slate-500">Status saat ini: {{ $application->status->label() }}.</p><a class="mt-3 inline-block font-semibold text-indigo-700" href="{{ route('applications.index') }}">Pantau lamaran →</a></div>
                    @else
                        <form class="card space-y-4 p-6" method="POST" enctype="multipart/form-data" action="{{ route('applications.store', $scholarship) }}">
                            @csrf
                            <div><h2 class="text-lg font-bold text-slate-950">{{ $application ? 'Lanjutkan draf lamaran' : 'Daftar beasiswa' }}</h2><p class="mt-1 text-sm text-slate-500">Setiap dokumen harus berupa PDF, maksimal 2 MB. CV, transkrip, dan surat motivasi wajib untuk mengirim lamaran.</p></div>
                            <label class="block text-sm font-medium">Motivasi
                                <textarea name="motivation" rows="4" maxlength="5000" class="field mt-1" placeholder="Catatan tambahan (opsional)">{{ old('motivation', $application?->motivation) }}</textarea>
                            </label>
                            @foreach (['cv' => 'CV', 'transcript' => 'Transkrip', 'motivation_letter' => 'Surat motivasi', 'language_certificate' => 'Sertifikat bahasa (opsional)'] as $field => $label)
                                @php($savedDocument = $application?->documents->firstWhere('document_type', $field))
                                <label class="block text-sm font-medium">{{ $label }}
                                    <input name="{{ $field }}" type="file" accept=".pdf,application/pdf" class="field mt-1">
                                    @if ($savedDocument)<span class="mt-1 block text-xs text-emerald-700">Tersimpan: {{ $savedDocument->original_name }}</span>@endif
                                    @error($field)<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
                                </label>
                            @endforeach
                            @error('intent')<p class="text-sm text-rose-700">{{ $message }}</p>@enderror
                            <button class="button-secondary w-full justify-center" type="submit" name="intent" value="draft">Simpan sebagai draf</button>
                            <button class="button-primary w-full justify-center" type="submit" name="intent" value="submit">Kirim lamaran</button>
                        </form>
                    @endif
                @endif
            @else
                <div class="card p-6"><h2 class="font-bold text-slate-950">Siap mendaftar?</h2><p class="mt-2 text-sm text-slate-500">Buat akun mahasiswa untuk menyimpan dan mengirim lamaran.</p><a class="button-primary mt-4 w-full justify-center" href="{{ route('register') }}">Buat akun</a></div>
            @endauth
        </aside>
    </section>
@endsection
