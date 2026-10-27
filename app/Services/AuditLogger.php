<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Central audit writer. Records who did what, when, from where. Call statically
 * from controllers/services at the point an important action occurs.
 *
 *   AuditLogger::log('document.downloaded', 'documents', $doc->id, ['title' => $doc->title]);
 */
class AuditLogger
{
    public static function log(
        string $action,
        string $module,
        ?string $subjectId = null,
        array $meta = [],
        ?string $subjectType = null,
        string $status = 'success',
    ): void {
        $user = Auth::user();

        AuditLog::create([
            'user_id' => $user?->id,
            'actor_name' => $user?->name,
            'actor_role' => $user?->role,
            'action' => $action,
            'status' => $status,
            'module' => $module,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'meta' => $meta ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }

    /** Audit a login for a specific user (Auth not yet established at that point). */
    public static function logFor(
        \App\Models\User $user,
        string $action,
        string $module,
        array $meta = [],
        string $status = 'success',
    ): void {
        AuditLog::create([
            'user_id' => $user->id,
            'actor_name' => $user->name,
            'actor_role' => $user->role,
            'action' => $action,
            'status' => $status,
            'module' => $module,
            'meta' => $meta ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }

    /** Audit a login attempt for an identifier that failed BEFORE resolving to a User (e.g. unknown vkollab_id). */
    public static function logFailure(
        string $action,
        string $module,
        array $meta = [],
    ): void {
        AuditLog::create([
            'action' => $action,
            'status' => 'failure',
            'module' => $module,
            'meta' => $meta ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }
}
