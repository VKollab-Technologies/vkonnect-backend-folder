<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds the two footer contact fields the Landing Page enhancement needs:
 * a phone number (tel: link) and a Google Maps URL (location link) — neither
 * existed before. Mirrors the seeder so fresh installs and already-seeded
 * databases both end up with these rows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::firstOrCreate(
            ['key' => 'footer_phone'],
            [
                'value' => '+91 80 4567 8900',
                'type' => 'string',
                'label' => 'Support phone number',
                'group' => 'footer',
            ],
        );

        Setting::firstOrCreate(
            ['key' => 'footer_map_url'],
            [
                'value' => 'https://www.google.com/maps/search/?api=1&query=VKollab+Technologies%2C+India',
                'type' => 'string',
                'label' => 'Google Maps URL for the footer address',
                'group' => 'footer',
            ],
        );
    }

    public function down(): void
    {
        Setting::where('key', 'footer_phone')->delete();
        Setting::where('key', 'footer_map_url')->delete();
    }
};
