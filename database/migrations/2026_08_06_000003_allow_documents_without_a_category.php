<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lets a document exist without a category.
 *
 * Super Admin can now delete a category that still holds documents. The
 * documents themselves must survive that — they are an alumnus's Form 16 and
 * payslips, and losing them because someone tidied up the taxonomy would be
 * unforgivable — so they are detached rather than cascaded, which requires the
 * column to be nullable.
 *
 * `nullOnDelete` replaces `restrictOnDelete` so the database enforces the same
 * intent even if a future code path deletes a category some other way: detach,
 * never destroy. HR is still blocked from deleting a non-empty category in the
 * controller; this only changes what happens when a deletion IS permitted.
 *
 * The column change and the foreign key are in a single Schema::table call so
 * they are applied together.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('category_id')->nullable()->change();
            $table->foreign('category_id')
                ->references('id')
                ->on('document_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Orphans would violate the restored NOT NULL, so they are re-pointed
        // at any surviving category first. Deliberately lossy in the sense that
        // the original category is gone either way — this only restores a
        // schema shape, it cannot restore deleted categories.
        $fallback = DB::table('document_categories')->orderBy('sort_order')->value('id');

        if ($fallback) {
            DB::table('documents')->whereNull('category_id')->update(['category_id' => $fallback]);
        }

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('category_id')->nullable(false)->change();
            $table->foreign('category_id')
                ->references('id')
                ->on('document_categories')
                ->restrictOnDelete();
        });
    }
};
