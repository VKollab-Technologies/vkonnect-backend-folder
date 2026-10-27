<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — editable site content blocks (Terms, Privacy, footer blurb, etc.).
 * Keyed by a stable slug; edited by the Super Admin, served publicly.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key', 40)->unique();   // terms | privacy | footer_about | ...
            $table->string('title', 160);
            $table->longText('body');               // plain-text / lightweight markdown
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
