<?php

use App\Models\ContentBlock;
use Illuminate\Database\Migrations\Migration;

/**
 * Makes the landing page's "About" heading/paragraph Super-Admin editable,
 * the same way Terms/Privacy already are — reuses the existing ContentBlock
 * architecture, no new tables or endpoints needed.
 */
return new class extends Migration
{
    public function up(): void
    {
        ContentBlock::firstOrCreate(
            ['key' => 'landing_about'],
            [
                'title' => 'The trusted enterprise home for VKollab alumni.',
                'body' => "VKONNECT is VKollab Technologies' enterprise alumni engagement platform — built to formalize and sustain the organization's relationship with its alumni community for the long term. It brings together verified alumni identity, professional networking, career opportunities, and secure document access in a single governed environment, so the relationship an alumnus built with VKollab continues to grow long after their tenure.",
            ],
        );
    }

    public function down(): void
    {
        ContentBlock::where('key', 'landing_about')->delete();
    }
};
