<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Drops the `maintenance_mode` setting.
 *
 * It was never read anywhere — no controller, middleware or service consulted
 * it — so the switch in Platform Settings did nothing but look like it worked,
 * which is worse than not offering it. Laravel's real maintenance mode
 * (`php artisan down`, APP_MAINTENANCE_DRIVER) is a separate framework
 * mechanism and is untouched.
 *
 * Removing the row is what removes it from the UI: the Settings page renders
 * whatever rows the API returns, and SettingsController::update() ignores keys
 * with no row.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::where('key', 'maintenance_mode')->delete();
    }

    public function down(): void
    {
        Setting::firstOrCreate(['key' => 'maintenance_mode'], [
            'value' => '0',
            'type' => 'boolean',
            'label' => 'Maintenance mode',
            'group' => 'access',
        ]);
    }
};
