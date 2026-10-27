<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Splits Contact Information out of the "General"/"Public Footer" groups into
 * its own "contact" group on the Platform Settings screen — purely a display
 * grouping change (PublicController::site() reads settings by key, not group,
 * so the public footer/API response is unaffected).
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::whereIn('key', ['support_email', 'footer_address', 'footer_phone', 'footer_map_url'])
            ->update(['group' => 'contact']);
    }

    public function down(): void
    {
        Setting::where('key', 'support_email')->update(['group' => 'general']);
        Setting::whereIn('key', ['footer_address', 'footer_phone', 'footer_map_url'])->update(['group' => 'footer']);
    }
};
