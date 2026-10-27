<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — announcements + careers.
 * NOTE: the careers entity is named `job_postings` (not `jobs`) to avoid a
 * collision with Laravel's queue `jobs` table. See docs/02-database-schema.md §5–6.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 180);
            $table->longText('body');
            $table->string('audience', 16)->default('all');  // all | alumni | hr
            $table->string('status', 16)->default('draft');   // draft | published
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'published_at']);
            $table->index('audience');
        });

        Schema::create('job_postings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 160);
            $table->string('organization', 160);
            $table->string('location', 120);
            $table->string('type', 24)->default('full_time'); // full_time | part_time | contract | internship
            $table->boolean('is_remote')->default(false);
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->string('status', 16)->default('open');     // draft | open | closed
            $table->date('closes_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('announcements');
    }
};
