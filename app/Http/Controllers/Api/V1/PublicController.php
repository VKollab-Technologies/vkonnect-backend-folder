<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

/**
 * Public, unauthenticated endpoints that feed the marketing/legal surface
 * (landing footer, Terms, Privacy). All content is DB-driven and Super-Admin
 * editable — nothing is hardcoded in the frontend.
 */
class PublicController extends Controller
{
    /** Footer + brand config for the public layout. */
    public function site(): JsonResponse
    {
        $get = fn (string $key, ?string $default = null) => optional(Setting::find($key))->typedValue() ?? $default;

        return response()->json([
            'platformName' => $get('platform_name', 'VKONNECT'),
            'footer' => [
                'about' => $get('footer_about', ''),
                'email' => $get('support_email', 'alumni@vkollab.com'),
                'address' => $get('footer_address', ''),
                'phone' => $get('footer_phone', ''),
                'mapUrl' => $get('footer_map_url', ''),
                'social' => [
                    'linkedin' => $get('social_linkedin', ''),
                    'youtube' => $get('social_youtube', ''),
                    'instagram' => $get('social_instagram', ''),
                ],
                'copyrightHolder' => $get('footer_copyright_holder', 'VKollab Technologies'),
            ],
            // The landing page's Hero/About/CTA sections — every remaining
            // piece of on-page copy that used to be hardcoded in JSX.
            'landing' => [
                'heroEyebrow' => $get('landing_hero_eyebrow', 'VKollab Alumni Network'),
                'ctaPrimaryLabel' => $get('landing_cta_primary_label', 'Join VKONNECT'),
                'ctaSecondaryLabel' => $get('landing_cta_secondary_label', 'Member Login'),
                'aboutPillars' => [
                    $get('landing_about_pillar_1', ''),
                    $get('landing_about_pillar_2', ''),
                    $get('landing_about_pillar_3', ''),
                    $get('landing_about_pillar_4', ''),
                ],
            ],
        ]);
    }

    /** A single editable content block (e.g. terms, privacy). */
    public function content(string $key): JsonResponse
    {
        $block = ContentBlock::where('key', $key)->firstOrFail();

        return response()->json([
            'key' => $block->key,
            'title' => $block->title,
            'body' => $block->body,
            'updatedAt' => $block->updated_at?->toIso8601String(),
        ]);
    }
}
