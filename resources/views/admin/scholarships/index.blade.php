@extends('layouts.app')
@section('title', 'Kelola beasiswa')
@section('content')
<section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-bold uppercase tracking-wider text-indigo-700">Panel administrator</p><h1 class="mt-2 text-3xl font-extrabold text-slate-950">Kelola beasiswa</h1><p class="mt-2 text-slate-500">Buat, perbarui, dan atur informasi beasiswa yang ditampilkan.</p></div><a class="button-primary" href="{{ route('admin.scholarships.create') }}">+ Tambah beasiswa</a></div>
    <div class="card overflow-x-auto">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4">Beasiswa</th><th class="px-5 py-4">Jenjang / negara</th><th class="px-5 py-4">Deadline</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($scholarships as $scholarship)
                    <tr><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $scholarship->title }}</p><p class="mt-1 text-slate-500">{{ $scholarship->provider }}</p></td><td class="px-5 py-4">{{ $scholarship->degree_level }} · {{ $scholarship->country }}</td><td class="px-5 py-4">{{ $scholarship->deadline->translatedFormat('d M Y') }}</td><td class="px-5 py-4"><span class="badge">{{ $scholarship->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td class="px-5 py-4"><div class="flex items-center gap-3"><a class="font-semibold text-indigo-700" href="{{ route('admin.scholarships.edit', $scholarship) }}">Ubah</a><form method="POST" action="{{ route('admin.scholarships.destroy', $scholarship) }}" onsubmit="return confirm('Hapus beasiswa ini?')">@csrf @method('DELETE')<button class="font-semibold text-rose-700" type="submit">Hapus</button></form></div></td></tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada beasiswa. Tambahkan beasiswa pertama.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $scholarships->links() }}</div>
</section>
@endsection
