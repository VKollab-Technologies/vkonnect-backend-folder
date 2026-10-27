<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `app_notifications` table — NOT `notifications`.
 *
 * The filename says notifications because that is the feature; the table is
 * prefixed to avoid colliding with Laravel's own notifications table, which
 * the framework creates if database notifications are ever enabled. Anyone
 * grepping for app_notifications would otherwise not find its creator.
 *
 * The filename is deliberately not corrected: Laravel tracks applied
 * migrations by name, so renaming one would make it re-run.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete(); // recipient
            $table->string('type', 32)->default('system'); // announcement | job | approval | system
            $table->string('title', 180);
            $table->string('body', 500)->nullable();
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
