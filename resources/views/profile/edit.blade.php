@extends('layouts.app')
@section('title', 'Profil mahasiswa')
@section('content')
<section class="mx-auto max-w-3xl px-5 py-12 lg:px-8">
    <div class="mb-7"><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Akun mahasiswa</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Profil akademik</h1><p class="mt-2 text-slate-500">Lengkapi profil agar persyaratan beasiswa lebih mudah dibandingkan.</p></div>
    <form class="card space-y-6 p-7" method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="text-sm font-semibold">IPK
                <input class="field mt-1" type="number" step="0.01" min="0" max="4" name="gpa" value="{{ old('gpa', $profile->gpa) }}" placeholder="Contoh: 3.75">
                @error('gpa')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="text-sm font-semibold">Tanggal lahir
                <input class="field mt-1" type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}">
                @error('birth_date')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="text-sm font-semibold">Negara asal
                <input class="field mt-1" type="text" name="country_of_origin" maxlength="100" value="{{ old('country_of_origin', $profile->country_of_origin) }}" placeholder="Contoh: Indonesia">
                @error('country_of_origin')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="text-sm font-semibold">Skor IELTS
                <input class="field mt-1" type="number" step="0.5" min="0" max="9" name="ielts_score" value="{{ old('ielts_score', $profile->ielts_score) }}" placeholder="0–9">
                @error('ielts_score')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
            <label class="text-sm font-semibold">Skor TOEFL iBT
                <input class="field mt-1" type="number" min="0" max="120" name="toefl_score" value="{{ old('toefl_score', $profile->toefl_score) }}" placeholder="0–120">
                @error('toefl_score')<span class="mt-1 block text-sm text-rose-700">{{ $message }}</span>@enderror
            </label>
        </div>
        <div class="flex justify-end"><button class="button-primary" type="submit">Simpan profil</button></div>
    </form>
</section>
@endsection
