<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * `require_hr_approval` is dead — alumni activation is now OTP-driven
 * (Phase 3), not gated by HR approval. Removed from Settings entirely.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::where('key', 'require_hr_approval')->delete();
    }

    public function down(): void
    {
        Setting::firstOrCreate(
            ['key' => 'require_hr_approval'],
            ['value' => '1', 'type' => 'boolean', 'label' => 'Require HR approval for new alumni', 'group' => 'access'],
        );
    }
};
