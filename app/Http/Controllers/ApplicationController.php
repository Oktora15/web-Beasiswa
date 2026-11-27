<?php

namespace App\Http\Controllers;

use App\ApplicationStatus;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Application::class);

        $applications = $request->user()
            ->applications()
            ->with(['scholarship', 'documents'])
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function show(Application $application): View
    {
        $this->authorize('view', $application);
        $application->load(['scholarship', 'documents']);

        return view('applications.show', compact('application'));
    }

    public function store(StoreApplicationRequest $request, Scholarship $scholarship): RedirectResponse
    {
        $this->authorize('create', Application::class);
        abort_unless($scholarship->is_active && $scholarship->deadline->greaterThanOrEqualTo(today()), 404);

        $student = $request->user();
        $application = $student->applications()
            ->with('documents')
            ->where('scholarship_id', $scholarship->id)
            ->first();

        if ($application) {
            $this->authorize('update', $application);
        }

        $isSubmitting = $request->validated('intent') === 'submit';
        $nextStatus = $isSubmitting ? ApplicationStatus::Submitted : ApplicationStatus::Draft;
        $documentTypes = [
            'cv' => 'CV',
            'transcript' => 'Transkrip',
            'motivation_letter' => 'Surat motivasi',
            'language_certificate' => 'Sertifikat bahasa',
        ];
        $storedPaths = [];
        $replacedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $student,
                $scholarship,
                $application,
                $isSubmitting,
                $nextStatus,
                $documentTypes,
                &$storedPaths,
                &$replacedPaths,
            ): void {
                $data = [
                    'scholarship_id' => $scholarship->id,
                    'status' => $nextStatus,
                    'motivation' => $request->validated('motivation'),
                    'submitted_at' => $isSubmitting ? now() : null,
                ];

                if ($application) {
                    $application->update($data);
                } else {
                    $application = $student->applications()->create($data);
                }

                foreach ($documentTypes as $field => $label) {
                    $document = $request->file($field);

                    if (! $document) {
                        continue;
                    }

                    $path = $document->store('applications/'.$application->id, 'local');

                    if (! is_string($path) || $path === '') {
                        throw new RuntimeException("Dokumen {$label} gagal disimpan.");
                    }

                    $storedPaths[] = $path;
                    $existingDocument = $application->documents()
                        ->where('document_type', $field)
                        ->first();
                    $documentData = [
                        'document_type' => $field,
                        'original_name' => Str::limit($document->getClientOriginalName(), 250, ''),
                        'path' => $path,
                        'mime_type' => $document->getMimeType(),
                        'size' => $document->getSize(),
                    ];

                    if ($existingDocument) {
                        $replacedPaths[] = $existingDocument->path;
                        $existingDocument->update($documentData);
                    } else {
                        $application->documents()->create($documentData);
                    }
                }
            });
        } catch (Throwable $exception) {
            if ($storedPaths !== [] && ! Storage::disk('local')->delete($storedPaths)) {
                throw new RuntimeException('Dokumen sementara gagal dibersihkan.', previous: $exception);
            }

            throw $exception;
        }

        if ($replacedPaths !== [] && ! Storage::disk('local')->delete($replacedPaths)) {
            throw new RuntimeException('Dokumen lama gagal dihapus setelah penggantian.');
        }

        $message = $isSubmitting
            ? 'Lamaran berhasil dikirim.'
            : 'Draf lamaran berhasil disimpan.';

        return redirect()->route('applications.index')->with('status', $message);
    }
}
