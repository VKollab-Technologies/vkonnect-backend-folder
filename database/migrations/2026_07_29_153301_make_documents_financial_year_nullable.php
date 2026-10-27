<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Not every document category is period-based (e.g. Offer Letter, Relieving
 * Letter are one-time documents), so the Financial Year field is no longer
 * always required at upload time — the column must allow that.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('financial_year', 9)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('financial_year', 9)->nullable(false)->change();
        });
    }
};
