<?php

namespace App\Exports;

use App\Http\Controllers\Api\V1\SuperAdmin\AuditLogController;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Exports audit / activity / login logs (with the same filters as the viewer)
 * to Excel/CSV.
 */
class AuditLogExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string,mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return AuditLogController::applyFilters(AuditLog::query()->latest('created_at'), $this->filters);
    }

    public function headings(): array
    {
        return ['Timestamp', 'Actor', 'Role', 'Action', 'Status', 'Module', 'Target', 'IP Address', 'Severity'];
    }

    /** @param  AuditLog  $log */
    public function map($log): array
    {
        $meta = $log->meta ?? [];
        $target = $log->module;
        foreach (['title', 'name', 'alumnus', 'user', 'owner', 'key', 'vkollabId', 'file'] as $k) {
            if (! empty($meta[$k])) {
                $target = (string) $meta[$k];
                break;
            }
        }

        return [
            $log->created_at?->toDateTimeString(),
            $log->actor_name ?? 'System',
            $log->actor_role,
            $log->action,
            $log->status,
            $log->module,
            $target,
            $log->ip_address,
            $log->severity(),
        ];
    }
}
