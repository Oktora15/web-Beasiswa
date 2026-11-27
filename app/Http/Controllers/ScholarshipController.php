<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScholarshipRequest;
use App\Models\Criterion;
use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'degree_level' => ['nullable', Rule::in(['S1', 'S2', 'S3'])],
            'funding_type' => ['nullable', Rule::in(['fully', 'partial'])],
            'no_language_test' => ['nullable', 'boolean'],
            'return_obligation' => ['nullable', 'boolean'],
            'min_gpa' => ['nullable', 'numeric', 'between:0,4'],
            'max_age' => ['nullable', 'integer', 'between:16,100'],
            'open' => ['nullable', 'boolean'],
        ], [
            'search.string' => 'Pencarian harus berupa teks.',
            'search.max' => 'Pencarian maksimal 255 karakter.',
            'country.string' => 'Negara harus berupa teks.',
            'country.max' => 'Negara maksimal 100 karakter.',
            'degree_level.in' => 'Jenjang yang dipilih tidak valid.',
            'funding_type.in' => 'Jenis pendanaan yang dipilih tidak valid.',
            'no_language_test.boolean' => 'Pilihan tes bahasa tidak valid.',
            'return_obligation.boolean' => 'Pilihan kewajiban kembali tidak valid.',
            'min_gpa.numeric' => 'IPK filter harus berupa angka.',
            'min_gpa.between' => 'IPK harus berada di antara 0 dan 4.',
            'max_age.integer' => 'Batas usia filter harus berupa bilangan bulat.',
            'max_age.between' => 'Usia harus berada di antara 16 dan 100 tahun.',
            'open.boolean' => 'Pilihan status pembukaan tidak valid.',
        ]);

        $scholarships = Scholarship::published()->filter($filters);
        $student = $request->user();

        if ($student?->role === 'student') {
            $scholarships->withCount([
                'bookmarks as is_bookmarked' => fn (Builder $query): Builder => $query->where('user_id', $student->id),
            ]);
        }

        $scholarships = $scholarships->paginate(9)->withQueryString();
        $countries = Scholarship::published()
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        return view('scholarships.index', compact('scholarships', 'countries', 'filters'));
    }

    public function adminIndex()
    {
        $this->authorize('viewAny', Scholarship::class);
        $scholarships = Scholarship::latest()->paginate(15);

        return view('admin.scholarships.index', compact('scholarships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Scholarship::class);

        return view('admin.scholarships.form', $this->scholarshipFormData(new Scholarship));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScholarshipRequest $request)
    {
        $this->authorize('create', Scholarship::class);
        $data = $request->validated();
        $criterionIds = $data['criteria'] ?? [];
        unset($data['criteria']);

        DB::transaction(function () use ($data, $criterionIds): void {
            $scholarship = Scholarship::create($data);
            $scholarship->criteria()->sync($criterionIds);
        });

        return redirect()->route('admin.scholarships.index')->with('status', 'Beasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scholarship $scholarship): View
    {
        abort_unless($scholarship->is_active, 404);
        $scholarship->load('criteria');
        $student = auth()->user();
        $isStudent = $student?->role === 'student';
        $application = $isStudent
            ? $student->applications()->with('documents')->where('scholarship_id', $scholarship->id)->first()
            : null;
        $bookmarked = $isStudent
            && $student->bookmarks()->where('scholarship_id', $scholarship->id)->exists();
        $profile = $isStudent ? $student->profile : null;
        $eligibility = $isStudent ? [
            'gpa' => $scholarship->min_gpa === null
                ? true
                : ($profile?->gpa === null ? null : (float) $profile->gpa >= (float) $scholarship->min_gpa),
            'age' => $scholarship->max_age === null
                ? true
                : ($profile?->birth_date === null ? null : $profile->birth_date->age <= $scholarship->max_age),
        ] : null;

        return view('scholarships.show', compact('scholarship', 'application', 'bookmarked', 'profile', 'eligibility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scholarship $scholarship)
    {
        $this->authorize('update', $scholarship);

        return view('admin.scholarships.form', $this->scholarshipFormData($scholarship));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScholarshipRequest $request, Scholarship $scholarship)
    {
        $this->authorize('update', $scholarship);
        $data = $request->validated();
        $criterionIds = $data['criteria'] ?? [];
        unset($data['criteria']);

        DB::transaction(function () use ($scholarship, $data, $criterionIds): void {
            $scholarship->update($data);
            $scholarship->criteria()->sync($criterionIds);
        });

        return redirect()->route('admin.scholarships.index')->with('status', 'Beasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scholarship $scholarship)
    {
        $this->authorize('delete', $scholarship);
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')->with('status', 'Beasiswa berhasil dihapus.');
    }

    /**
     * @return array{scholarship: Scholarship, criteria: Collection, selectedCriteria: array}
     */
    private function scholarshipFormData(Scholarship $scholarship): array
    {
        $selectedCriteria = old(
            'criteria',
            $scholarship->exists ? $scholarship->criteria()->pluck('criteria.id')->all() : [],
        );
        $selectedCriteria = is_array($selectedCriteria)
            ? array_map('intval', array_filter($selectedCriteria, 'is_scalar'))
            : [];

        return [
            'scholarship' => $scholarship,
            'criteria' => Criterion::query()->orderBy('name')->get(),
            'selectedCriteria' => $selectedCriteria,
        ];
    }
}
