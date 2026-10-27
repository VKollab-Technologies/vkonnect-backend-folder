<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — live alert ticker. Short, time-boxed messages shown in the
 * scrolling ticker across dashboards. Managed by HR (ticker.manage) and also
 * auto-created on key events (e.g. an announcement being published).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('message', 200);
            $table->string('level', 16)->default('info');   // info | success | warning | critical
            $table->string('link', 255)->nullable();
            $table->string('audience', 16)->default('all');  // all | alumni | hr
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'audience']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
