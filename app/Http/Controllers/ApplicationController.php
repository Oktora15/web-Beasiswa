<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Scholarship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->applications()->with('scholarship')->latest()->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function store(StoreApplicationRequest $request, Scholarship $scholarship)
    {
        abort_unless($scholarship->is_active && $scholarship->deadline->greaterThanOrEqualTo(today()), 404);
        $application = auth()->user()->applications()->where('scholarship_id', $scholarship->id)->first();
        abort_if($application && $application->status !== 'draft', 409, 'Anda sudah mendaftar beasiswa ini.');
        $files = $request->file('documents', []);

        if ($application && $application->documents()->count() + count($files) > 8) {
            throw ValidationException::withMessages(['documents' => 'Maksimal 8 dokumen per lamaran.']);
        }

        DB::transaction(function () use ($request, $scholarship, $application, $files): void {
            $data = [
                'scholarship_id' => $scholarship->id,
                'status' => $request->validated('intent') === 'submit' ? 'submitted' : 'draft',
                'motivation' => $request->validated('motivation'),
                'submitted_at' => $request->validated('intent') === 'submit' ? now() : null,
            ];
            if ($application) {
                $application->update($data);
            } else {
                $application = auth()->user()->applications()->create($data);
            }

            foreach ($files as $document) {
                $path = $document->store('applications/'.$application->id, 'local');
                $application->documents()->create([
                    'document_type' => Str::limit(pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME), 250, ''),
                    'original_name' => Str::limit($document->getClientOriginalName(), 250, ''),
                    'path' => $path,
                    'mime_type' => $document->getMimeType(),
                    'size' => $document->getSize(),
                ]);
            }
        });

        $message = $request->validated('intent') === 'submit' ? 'Lamaran berhasil dikirim.' : 'Draf lamaran berhasil disimpan.';

        return redirect()->route('applications.index')->with('status', $message);
    }
}
