<?php

namespace App\Services;

use App\Models\Setting;

/**
 * Shared, DB-driven branding values for outgoing email — Super-Admin-editable
 * (via the same `settings` table already backing the frontend footer), never
 * hardcoded into a Blade template. Falls back to sensible defaults if a
 * setting row is missing.
 */
class EmailBranding
{
    /** @return array{supportEmail: string, platformName: string} */
    public static function values(): array
    {
        return [
            'supportEmail' => (string) (Setting::find('support_email')?->typedValue() ?? 'alumni@vkollab.com'),
            'platformName' => (string) (Setting::find('platform_name')?->typedValue() ?? 'VKONNECT'),
        ];
    }
}
