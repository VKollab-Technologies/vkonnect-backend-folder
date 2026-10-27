<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Audit payloads must survive storage byte-for-byte.
 *
 * `meta` was declared json. On MySQL that is a native JSON type, which parses
 * the value and re-serialises it on the way out: object keys come back in
 * MySQL's own order with its own spacing. The migration onto MySQL surfaced this
 * on 382 of 1502 rows — semantically identical every time, but no longer the
 * same bytes that were written.
 *
 * For most columns that would be a non-issue. Audit logs are the one place it
 * is not: they exist to evidence what happened, and an evidentiary record that
 * silently rewrites itself in storage is weaker than one that does not.
 *
 * Nothing reads this column through native JSON SQL — no JSON_EXTRACT, no
 * whereJsonContains anywhere in the codebase — and AuditLog casts `meta` to
 * 'array', which is Laravel-side json_encode/json_decode and behaves the same
 * over TEXT. So the type change costs nothing and buys exact retention.
 *
 * longText rather than text: text caps at 64KB, and a truncated audit entry is
 * exactly the failure this migration exists to prevent.
 *
 * Written driver-agnostically so fresh installs and this database end up with
 * the same declared type.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function ($table) {
            $table->longText('meta')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function ($table) {
            $table->json('meta')->nullable()->change();
        });
    }
};
