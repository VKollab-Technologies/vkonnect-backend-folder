<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Alumni Master Data Management — schema extensions.
 *  - Adds `dob` and `current_designation` to the alumni roster.
 *  - Adds `alumni_import_batches` to persist upload history (who/when/counts/
 *    failed rows/IP) for the enterprise import UI.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_masters', function (Blueprint $table) {
            if (! Schema::hasColumn('alumni_masters', 'dob')) {
                $table->date('dob')->nullable()->after('location');
            }
            if (! Schema::hasColumn('alumni_masters', 'current_designation')) {
                $table->string('current_designation', 120)->nullable()->after('organization');
            }
            // (email is already indexed by the original alumni_masters migration)
        });

        Schema::create('alumni_import_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('uploaded_by_name', 120)->nullable();
            $table->string('original_filename', 200)->nullable();
            $table->string('mode', 16)->default('update');          // skip | update | create
            $table->string('status', 16)->default('processing');     // processing | completed | failed
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->longText('failed_rows')->nullable();             // JSON: [{row, vkollabId, reasons[]}]
            $table->text('error')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['uploaded_by', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_import_batches');
        Schema::table('alumni_masters', function (Blueprint $table) {
            $table->dropColumn(['dob', 'current_designation']);
        });
    }
};
