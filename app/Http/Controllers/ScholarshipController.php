<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScholarshipRequest;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('scholarships.index');
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

        return view('admin.scholarships.form', ['scholarship' => new Scholarship]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScholarshipRequest $request)
    {
        Scholarship::create($request->validated());

        return redirect()->route('admin.scholarships.index')->with('status', 'Beasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scholarship $scholarship)
    {
        abort_unless($scholarship->is_active, 404);
        $application = auth()->check() && auth()->user()->role === 'student'
            ? auth()->user()->applications()->with('documents')->where('scholarship_id', $scholarship->id)->first()
            : null;
        $bookmarked = auth()->check() && auth()->user()->role === 'student'
            && auth()->user()->bookmarks()->where('scholarship_id', $scholarship->id)->exists();

        return view('scholarships.show', compact('scholarship', 'application', 'bookmarked'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scholarship $scholarship)
    {
        $this->authorize('update', $scholarship);

        return view('admin.scholarships.form', compact('scholarship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScholarshipRequest $request, Scholarship $scholarship)
    {
        $scholarship->update($request->validated());

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
}
