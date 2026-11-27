<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'scholarships' => Scholarship::count(),
            'open_scholarships' => Scholarship::published()->currentlyOpen()->count(),
            'applications' => Application::count(),
            'pending_applications' => Application::whereIn('status', ['submitted', 'review'])->count(),
        ];
        $applications = Application::with(['user', 'scholarship'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'applications'));
    }
}
