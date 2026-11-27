<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['user', 'scholarship'])->latest()->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    public function update(Request $request, Application $application)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['review', 'accepted', 'rejected'])],
        ]);
        $application->update($data);

        return back()->with('status', 'Status lamaran berhasil diperbarui.');
    }

    public function downloadDocument(ApplicationDocument $document)
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }
}
