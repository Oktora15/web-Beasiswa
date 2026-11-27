<div style="font-family:Arial,sans-serif;color:#1e293b;line-height:1.6">
    <h1>Pengingat deadline beasiswa</h1>
    <p>Halo {{ $user->name ?? 'Mahasiswa' }},</p>
    <p>Beasiswa <strong>{{ $scholarship->title }}</strong> yang Anda simpan akan ditutup dalam {{ $daysRemaining }} hari, pada {{ $scholarship->deadline->translatedFormat('d F Y') }}.</p>
    <p><a href="{{ route('scholarships.show', $scholarship) }}">Lihat detail dan siapkan pendaftaran</a></p>
</div>
