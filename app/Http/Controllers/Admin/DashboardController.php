<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $applicationCounts = Application::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $applicationStatuses = array_map(
            fn (ApplicationStatus $status): array => [
                'label' => $status->label(),
                'count' => (int) $applicationCounts->get($status->value, 0),
            ],
            ApplicationStatus::cases(),
        );
        $maxStatusCount = max(array_column($applicationStatuses, 'count'));
        $applicationStatuses = array_map(
            fn (array $status): array => $status + [
                'bar_width' => $maxStatusCount > 0 ? round($status['count'] / $maxStatusCount * 100, 1) : 0,
            ],
            $applicationStatuses,
        );
        $stats = [
            'scholarships' => Scholarship::count(),
            'open_scholarships' => Scholarship::published()->currentlyOpen()->count(),
            'applications' => Application::count(),
            'pending_applications' => Application::whereIn('status', ['submitted', 'review'])->count(),
        ];
        $applications = Application::with(['user', 'scholarship'])->latest()->limit(8)->get();
        $mostAppliedScholarships = Scholarship::withCount('applications')
            ->orderByDesc('applications_count')
            ->orderBy('title')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'applications', 'applicationStatuses', 'mostAppliedScholarships'));
    }
}
