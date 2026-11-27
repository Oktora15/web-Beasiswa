<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::enum(ApplicationStatus::class)],
        ], [
            'status.enum' => 'Filter status lamaran tidak valid.',
        ]);
        $applications = Application::with(['user', 'scholarship', 'documents'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'filters' => $filters,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    public function show(Application $application): View
    {
        $application->load(['user.profile', 'scholarship', 'documents']);

        return view('admin.applications.show', compact('application'));
    }

    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                ApplicationStatus::Review->value,
                ApplicationStatus::Accepted->value,
                ApplicationStatus::Rejected->value,
            ])],
        ], [
            'status.required' => 'Pilih status lamaran yang akan diterapkan.',
            'status.in' => 'Status lamaran yang dipilih tidak valid.',
        ]);
        $nextStatus = ApplicationStatus::from($data['status']);

        if (! $application->status->canTransitionTo($nextStatus)
            || ! in_array($nextStatus, $application->status->adminTransitions(), true)) {
            throw ValidationException::withMessages([
                'status' => 'Status lamaran tidak dapat diubah ke tahap tersebut.',
            ]);
        }

        $application->update(['status' => $nextStatus]);

        return back()->with('status', 'Status lamaran berhasil diperbarui.');
    }

    public function downloadDocument(ApplicationDocument $document)
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }
}
