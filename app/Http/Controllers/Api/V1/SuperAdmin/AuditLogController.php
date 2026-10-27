<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Exports\AuditLogExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Services\AuditLogger;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Audit / activity / login log viewer + export. The audit_logs table is the
 * single source — logins are rows with action `auth.login` (module `auth`).
 */
class AuditLogController extends Controller
{
    /** Shared filter application — keeps the viewer and the export identical. */
    public static function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['module'] ?? null, fn (Builder $q, $m) => $q->where('module', $m))
            ->when($filters['action'] ?? null, fn (Builder $q, $a) => $q->where('action', $a))
            ->when($filters['status'] ?? null, fn (Builder $q, $s) => $q->where('status', $s))
            ->when($filters['search'] ?? null, function (Builder $q, $term) {
                $q->where(fn ($s) => $s
                    ->where('actor_name', 'like', "%{$term}%")
                    ->orWhere('action', 'like', "%{$term}%")
                    ->orWhere('ip_address', 'like', "%{$term}%"));
            })
            ->when($filters['from'] ?? null, fn (Builder $q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $q, $to) => $q->whereDate('created_at', '<=', $to));
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->integer('perPage', 20), 100);
        $filters = $request->only('search', 'module', 'action', 'status', 'from', 'to');

        $logs = self::applyFilters(AuditLog::query()->latest('created_at'), $filters)->paginate($perPage);

        return response()->json(ApiResponse::paginate($logs, AuditLogResource::class));
    }

    /** Distinct modules + actions for the filter dropdowns. */
    public function filters(): JsonResponse
    {
        return response()->json([
            'modules' => AuditLog::query()->distinct()->orderBy('module')->pluck('module')->filter()->values(),
            'actions' => AuditLog::query()->distinct()->orderBy('action')->pluck('action')->filter()->values(),
        ]);
    }

    public function export(Request $request): BinaryFileResponse|StreamedResponse
    {
        $filters = $request->only('search', 'module', 'action', 'status', 'from', 'to');

        AuditLogger::log('audit.exported', 'reports', null, ['filters' => array_filter($filters)]);

        $format = $request->string('format') == 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
        $ext = $format === \Maatwebsite\Excel\Excel::CSV ? 'csv' : 'xlsx';

        return Excel::download(new AuditLogExport($filters), 'audit-logs-'.now()->format('Y-m-d').".{$ext}", $format);
    }
}
