<?php

use App\Models\Setting;
use App\Support\PlatformFeatures;
use Illuminate\Database\Migrations\Migration;

/**
 * Tier 1 of the Service Request gate: the Super Admin platform toggle.
 *
 * Off by default, deliberately. The page behind it is still a placeholder, so
 * enabling it network-wide by default would put a "coming soon" item in every
 * alumnus's sidebar without anyone choosing to.
 *
 * `updateOrCreate` rather than `create`: migrations run on databases that may
 * already carry the row from a seeder, and re-running must not collide.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => PlatformFeatures::SERVICE_REQUESTS],
            [
                'value' => '0',
                'type' => 'boolean',
                'label' => 'Service Request',
                'group' => 'access',
            ],
        );
    }

    public function down(): void
    {
        Setting::where('key', PlatformFeatures::SERVICE_REQUESTS)->delete();
    }
};
