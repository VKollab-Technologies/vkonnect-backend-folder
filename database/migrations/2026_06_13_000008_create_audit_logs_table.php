<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — immutable audit trail of every important action (login, logout,
 * upload, download, create, edit, delete, permission change, status change).
 * Append-only. Designed for high volume (1M+ rows) with targeted indexes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_name', 120)->nullable();   // denormalized for fast reads
            $table->string('actor_role', 32)->nullable();
            $table->string('action', 64);                    // e.g. auth.login, document.downloaded
            $table->string('module', 40);                    // e.g. auth, documents, permissions
            $table->string('subject_type', 120)->nullable();
            $table->uuid('subject_id')->nullable();
            $table->json('meta')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'created_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
