<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds the "Send to Email" feature flag.
 *
 * A migration as well as a seeder entry: the seeder only runs on a fresh
 * install, so without this an already-deployed environment would have no row
 * for the key — and SettingsController::update() silently ignores keys with no
 * row, so the toggle would appear in no UI and could never be switched on.
 *
 * Defaults OFF. The feature emails a link to employment documents, so it
 * should be a deliberate decision to enable it, not something that arrives
 * switched on with a deploy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(['key' => 'alumni_document_email_enabled'], [
            'value' => '0',
            'type' => 'boolean',
            'label' => 'Let alumni email their own documents to themselves',
            'group' => 'access',
        ]);
    }

    public function down(): void
    {
        Setting::where('key', 'alumni_document_email_enabled')->delete();
    }
};
