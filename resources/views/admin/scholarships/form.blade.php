@extends('layouts.app')
@section('title', $scholarship->exists ? 'Ubah beasiswa' : 'Tambah beasiswa')
@section('content')
<section class="mx-auto max-w-4xl px-5 py-12 lg:px-8">
    <div class="mb-7"><a class="text-sm font-semibold text-indigo-700" href="{{ route('admin.scholarships.index') }}">← Kelola beasiswa</a><h1 class="mt-4 text-3xl font-extrabold text-slate-950">{{ $scholarship->exists ? 'Ubah beasiswa' : 'Tambah beasiswa' }}</h1></div>
    <form class="card space-y-6 p-6 sm:p-8" method="POST" action="{{ $scholarship->exists ? route('admin.scholarships.update', $scholarship) : route('admin.scholarships.store') }}">
        @csrf @if($scholarship->exists) @method('PUT') @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="text-sm font-semibold sm:col-span-2">Nama beasiswa<input class="field mt-1" name="title" value="{{ old('title', $scholarship->title) }}" required>@error('title')<small class="text-rose-700">{{ $message }}</small>@enderror</label>
            <label class="text-sm font-semibold">Slug URL<input class="field mt-1" name="slug" value="{{ old('slug', $scholarship->slug) }}" required placeholder="nama-program-beasiswa">@error('slug')<small class="text-rose-700">{{ $message }}</small>@enderror</label>
            <label class="text-sm font-semibold">Penyedia<input class="field mt-1" name="provider" value="{{ old('provider', $scholarship->provider) }}" required></label>
            <label class="text-sm font-semibold">Negara<input class="field mt-1" name="country" value="{{ old('country', $scholarship->country) }}" required></label>
            <label class="text-sm font-semibold">Jenjang<select class="field mt-1" name="degree_level" required>@foreach(['S1','S2','S3'] as $value)<option value="{{ $value }}" @selected(old('degree_level', $scholarship->degree_level) === $value)>{{ $value }}</option>@endforeach</select></label>
            <label class="text-sm font-semibold">Jenis pendanaan<select class="field mt-1" name="funding_type" required><option value="fully" @selected(old('funding_type', $scholarship->funding_type) === 'fully')>Penuh</option><option value="partial" @selected(old('funding_type', $scholarship->funding_type) === 'partial')>Parsial</option></select></label>
            <label class="text-sm font-semibold">Syarat tes bahasa<select class="field mt-1" name="language_test" required>@foreach(['none'=>'Tanpa tes','ielts'=>'IELTS','toefl'=>'TOEFL','ielts_or_toefl'=>'IELTS atau TOEFL','waiver'=>'Bisa waiver'] as $value=>$label)<option value="{{ $value }}" @selected(old('language_test', $scholarship->language_test ?? 'none') === $value)>{{ $label }}</option>@endforeach</select></label>
            <label class="text-sm font-semibold">Kewajiban kembali<select class="field mt-1" name="return_obligation" required><option value="0" @selected(!old('return_obligation', $scholarship->return_obligation))>Tidak wajib</option><option value="1" @selected((bool)old('return_obligation', $scholarship->return_obligation))>Wajib</option></select></label>
            <label class="text-sm font-semibold">Durasi kembali (tahun)<input class="field mt-1" type="number" min="1" max="99" name="return_duration_years" value="{{ old('return_duration_years', $scholarship->return_duration_years) }}"></label>
            <label class="text-sm font-semibold">IPK minimum<input class="field mt-1" type="number" min="0" max="4" step="0.01" name="min_gpa" value="{{ old('min_gpa', $scholarship->min_gpa) }}"></label>
            <label class="text-sm font-semibold">Usia maksimum<input class="field mt-1" type="number" min="16" max="100" name="max_age" value="{{ old('max_age', $scholarship->max_age) }}"></label>
            <label class="text-sm font-semibold">Tanggal dibuka<input class="field mt-1" type="date" name="open_date" value="{{ old('open_date', $scholarship->open_date?->format('Y-m-d')) }}"></label>
            <label class="text-sm font-semibold">Deadline<input class="field mt-1" type="date" name="deadline" value="{{ old('deadline', $scholarship->deadline?->format('Y-m-d')) }}" required></label>
            <label class="text-sm font-semibold sm:col-span-2">URL resmi<input class="field mt-1" type="url" name="official_url" value="{{ old('official_url', $scholarship->official_url) }}" required></label>
            <label class="text-sm font-semibold sm:col-span-2">Deskripsi<textarea class="field mt-1" name="description" rows="6" required>{{ old('description', $scholarship->description) }}</textarea></label>
            <label class="text-sm font-semibold sm:col-span-2">Visibilitas<select class="field mt-1" name="is_active" required><option value="1" @selected(old('is_active', $scholarship->exists ? $scholarship->is_active : true))>Aktif dan tampil</option><option value="0" @selected(!old('is_active', $scholarship->exists ? $scholarship->is_active : true))>Nonaktif</option></select></label>
        </div>
        @if($errors->any())<div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-800">Periksa kembali data beasiswa. {{ $errors->first() }}</div>@endif
        <div class="flex justify-end gap-3"><a class="button-secondary" href="{{ route('admin.scholarships.index') }}">Batal</a><button class="button-primary" type="submit">Simpan beasiswa</button></div>
    </form>
</section>
@endsection
