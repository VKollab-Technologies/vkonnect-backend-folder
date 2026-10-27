<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * `session_timeout_minutes`, `otp_ttl_minutes`, and `max_login_attempts` were
 * editable in the Settings UI but never read by any backend code — OTP TTL
 * and lockout policy are fixed platform constants (see AuthService,
 * OtpService), and Sanctum tokens do not expire. Editing them did nothing.
 * Removed to stop the UI from implying they were configurable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::whereIn('key', [
            'session_timeout_minutes',
            'otp_ttl_minutes',
            'max_login_attempts',
        ])->delete();
    }

    public function down(): void
    {
        Setting::firstOrCreate(
            ['key' => 'session_timeout_minutes'],
            ['value' => '480', 'type' => 'integer', 'label' => 'Session timeout (minutes)', 'group' => 'security'],
        );
        Setting::firstOrCreate(
            ['key' => 'otp_ttl_minutes'],
            ['value' => '10', 'type' => 'integer', 'label' => 'OTP validity (minutes)', 'group' => 'security'],
        );
        Setting::firstOrCreate(
            ['key' => 'max_login_attempts'],
            ['value' => '5', 'type' => 'integer', 'label' => 'Max login attempts before lockout', 'group' => 'security'],
        );
    }
};
