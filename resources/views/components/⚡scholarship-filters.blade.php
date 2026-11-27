<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Scholarship;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $country = '';
    public string $degree_level = '';
    public string $funding_type = '';
    public string $language_test = '';
    public string $return_obligation = '';
    public string $min_gpa = '';
    public string $max_age = '';
    public bool $open = false;
    public string $sort = 'deadline_asc';

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'country' => $this->country,
            'degree_level' => $this->degree_level,
            'funding_type' => $this->funding_type,
            'language_test' => $this->language_test,
            'return_obligation' => $this->return_obligation,
            'min_gpa' => $this->min_gpa,
            'max_age' => $this->max_age,
            'open' => $this->open,
            'sort' => $this->sort,
        ];

        return view('components.⚡scholarship-filters', [
            'scholarships' => Scholarship::published()->filter($filters)->paginate(9),
            'countries' => Scholarship::published()->select('country')->distinct()->orderBy('country')->pluck('country'),
        ]);
    }
};
?>

<div class="space-y-7">
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <label class="xl:col-span-2">
            <span class="sr-only">Cari beasiswa</span>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nama, penyedia, atau negara..." class="field">
        </label>
        <select wire:model.live="country" class="field">
            <option value="">Semua negara</option>
            @foreach ($countries as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach
        </select>
        <select wire:model.live="degree_level" class="field">
            <option value="">Semua jenjang</option><option>S1</option><option>S2</option><option>S3</option>
        </select>
        <select wire:model.live="funding_type" class="field">
            <option value="">Semua pendanaan</option><option value="fully">Penuh</option><option value="partial">Parsial</option>
        </select>
        <select wire:model.live="language_test" class="field">
            <option value="">Semua syarat bahasa</option>
            <option value="none">Tanpa tes</option><option value="ielts">IELTS</option><option value="toefl">TOEFL</option>
            <option value="ielts_or_toefl">IELTS atau TOEFL</option><option value="waiver">Bisa waiver</option>
        </select>
        <select wire:model.live="return_obligation" class="field">
            <option value="">Kewajiban kembali: semua</option><option value="1">Wajib kembali</option><option value="0">Tidak wajib</option>
        </select>
        <input wire:model.live.debounce.300ms="min_gpa" type="number" min="0" max="4" step="0.01" placeholder="IPK Anda" class="field">
        <input wire:model.live.debounce.300ms="max_age" type="number" min="16" max="100" placeholder="Usia Anda" class="field">
    </div>
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
            <input wire:model.live="open" type="checkbox" class="size-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            Hanya yang masih dibuka
        </label>
        <select wire:model.live="sort" class="field max-w-xs">
            <option value="deadline_asc">Deadline terdekat</option><option value="deadline_desc">Deadline terjauh</option>
        </select>
    </div>

    <div wire:loading class="text-sm font-medium text-indigo-600">Memuat beasiswa…</div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($scholarships as $scholarship)
            <article class="card flex flex-col p-6">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <span class="badge">{{ $scholarship->country }}</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-indigo-700">{{ $scholarship->degree_level }}</span>
                </div>
                <h3 class="text-lg font-bold leading-snug text-slate-900">{{ $scholarship->title }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $scholarship->provider }}</p>
                <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-600">{{ $scholarship->description }}</p>
                <div class="mt-5 flex flex-wrap gap-2 text-xs">
                    <span class="badge">{{ $scholarship->funding_type === 'fully' ? 'Pendanaan penuh' : 'Pendanaan parsial' }}</span>
                    @if ($scholarship->min_gpa)<span class="badge">IPK min. {{ $scholarship->min_gpa }}</span>@endif
                </div>
                <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-5 mt-6">
                    <div><p class="text-xs text-slate-500">Batas pendaftaran</p><p class="font-semibold text-slate-800">{{ $scholarship->deadline->translatedFormat('d M Y') }}</p></div>
                    <a href="{{ route('scholarships.show', $scholarship) }}" class="button-secondary">Lihat detail</a>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center md:col-span-2 xl:col-span-3">
                <p class="font-semibold text-slate-800">Beasiswa tidak ditemukan</p>
                <p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
        @endforelse
    </div>
    <div>{{ $scholarships->links() }}</div>
</div>