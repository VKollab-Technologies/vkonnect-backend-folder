<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rename the `employee_id` column to `vkollab_id` across every table that
 * carries it. Guarded by hasColumn so it is safe on existing databases (renames
 * in place, preserving data + indexes) and on fresh installs (the prior
 * migrations create `employee_id`, this one renames it).
 */
return new class extends Migration
{
    private array $tables = ['users', 'alumni_profiles', 'hr_admins', 'alumni_masters'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'employee_id') && ! Schema::hasColumn($table, 'vkollab_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->renameColumn('employee_id', 'vkollab_id');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'vkollab_id') && ! Schema::hasColumn($table, 'employee_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->renameColumn('vkollab_id', 'employee_id');
                });
            }
        }
    }
};
