<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VKONNECT — platform settings (key/value). Editable by the Super Admin; every
 * change is persisted here and read back across the app.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 64)->primary();
            $table->text('value')->nullable();
            $table->string('type', 16)->default('string'); // string | boolean | integer
            $table->string('label', 120)->nullable();
            $table->string('group', 32)->default('general');
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
