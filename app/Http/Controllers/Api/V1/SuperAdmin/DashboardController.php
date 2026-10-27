<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Exports\DashboardReportExport;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Announcement;
use App\Models\AppNotification;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Super Admin platform overview. Every value is computed live from the database
 * — no hardcoded figures.
 */
class DashboardController extends Controller
{
    /**
     * Download the dashboard as .xlsx — KPI values, System Health and the
     * three graphs as native Excel charts.
     *
     * Built from payload() (the exact same array the screen renders) and a
     * live health check, so the export cannot drift away from the dashboard
     * it claims to represent.
     */
    public function export(HealthController $health): StreamedResponse
    {
        $healthPayload = json_decode($health()->getContent(), true) ?? [];

        $book = (new DashboardReportExport($this->payload(), $healthPayload))->build();

        $writer = new Xlsx($book);
        $writer->setIncludeCharts(true); // without this the charts are silently dropped

        $filename = 'vkonnect-dashboard-'.now()->format('Y-m-d').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function __invoke(): JsonResponse
    {
        return response()->json($this->payload());
    }

    /** The single source of truth for the dashboard — served and exported. */
    private function payload(): array
    {
        $totalAlumni = User::where('role', 'alumni')->count();
        $totalHrAdmins = User::where('role', 'hr_admin')->count();
        $totalSuperAdmins = User::where('role', 'super_admin')->count();
        $totalActive = User::where('status', 'active')->count();
        $totalDisabled = User::where('status', 'disabled')->count();

        return [
            'stats' => [
                'totalAlumni' => $totalAlumni,
                'totalHrAdmins' => $totalHrAdmins,
                'totalSuperAdmins' => $totalSuperAdmins,
                'totalJobs' => JobPosting::count(),
                'totalDocuments' => Document::count(),
                'totalAnnouncements' => Announcement::count(),
                'totalActiveUsers' => $totalActive,
                'totalDisabledUsers' => $totalDisabled,
                'totalApplications' => JobApplication::count(),
                'totalNotifications' => AppNotification::count(),
                'totalActiveAlertTickers' => Alert::where('is_active', true)->count(),
            ],
            'alumniGrowth' => $this->alumniGrowth(),
            'userStatusBreakdown' => [
                ['label' => 'Active', 'value' => $totalActive, 'color' => '#16A34A'],
                ['label' => 'Disabled', 'value' => $totalDisabled, 'color' => '#DC2626'],
            ],
            'documentsByCategory' => $this->documentsByCategory(),
            'recentActivity' => $this->recentActivity(),
            'recentActions' => $this->recentActions(),
        ];
    }

    /** Cumulative alumni count per month over the last 7 months (real data). */
    private function alumniGrowth(): array
    {
        $points = [];
        $cursor = now()->copy()->subMonths(6)->startOfMonth();

        for ($i = 0; $i < 7; $i++) {
            $end = $cursor->copy()->endOfMonth();
            $points[] = [
                'label' => $cursor->format('M'),
                'value' => User::where('role', 'alumni')->where('created_at', '<=', $end)->count(),
            ];
            $cursor->addMonth();
        }

        return $points;
    }

    /** Real document counts grouped by category. */
    private function documentsByCategory(): array
    {
        return DocumentCategory::orderBy('sort_order')
            ->withCount('documents')
            ->get()
            ->map(fn (DocumentCategory $c) => ['label' => $c->name, 'value' => $c->documents_count])
            ->all();
    }

    /** Most recently registered alumni rendered as activity entries. */
    private function recentActivity(): array
    {
        return User::where('role', 'alumni')
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'type' => 'approval',
                'actor' => $u->name,
                'description' => 'joined the alumni network',
                'timestamp' => optional($u->created_at)->toIso8601String() ?? Carbon::now()->toIso8601String(),
            ])
            ->all();
    }

    /** Real audit-log feed of recent sensitive actions. */
    private function recentActions(): array
    {
        return AuditLog::query()
            ->latest('created_at')
            ->limit(8)
            ->get()
            ->map(fn (AuditLog $a) => [
                'id' => $a->id,
                'actor' => $a->actor_name ?? 'System',
                'action' => $a->action,
                'target' => $a->meta['title']
                    ?? $a->meta['user']
                    ?? $a->meta['alumnus']
                    ?? $a->meta['name']
                    ?? ucfirst($a->module),
                'ip' => $a->ip_address ?? '—',
                'timestamp' => optional($a->created_at)->toIso8601String() ?? now()->toIso8601String(),
                'severity' => $a->severity(),
            ])
            ->all();
    }
}
