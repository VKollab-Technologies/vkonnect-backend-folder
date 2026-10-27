<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — secure documents + job applications.
 * Documents are stored on a PRIVATE disk; access is always mediated by a
 * short-lived signed URL (never a public path). See docs/02-database-schema.md §4–5.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 80);
            $table->string('slug', 80)->unique();
            $table->string('description', 255)->nullable();
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();       // owning alumnus
            $table->foreignUuid('category_id')->constrained('document_categories')->restrictOnDelete();
            $table->foreignUuid('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 160);
            $table->string('financial_year', 9);          // e.g. 2024-2025
            $table->string('disk_path');                   // PRIVATE disk path
            $table->string('original_name', 200);
            $table->string('mime_type', 120);
            $table->unsignedInteger('size_kb');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'category_id']);
            $table->index(['user_id', 'financial_year']);
        });

        Schema::create('document_downloads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('downloaded_at');
            $table->index(['document_id']);
            $table->index(['user_id', 'downloaded_at']);
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('submitted'); // submitted|under_review|shortlisted|rejected|hired
            $table->string('resume_path')->nullable();           // private disk
            $table->text('cover_note')->nullable();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('applied_at');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['job_posting_id', 'user_id']);   // one application per role per user
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('document_downloads');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_categories');
    }
};
