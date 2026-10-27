<?php

use App\Models\ContentBlock;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Closes the "landing page isn't 100% CMS-driven" gap: the Hero and CTA
 * sections' headline/subtext, the About section's 4 pillar bullets, and the
 * footer copyright holder name were hardcoded in JSX with no backing
 * Setting/ContentBlock row at all. Mirrors the same firstOrCreate-by-key
 * pattern DatabaseSeeder::seedContent()/seedSettings() already use, so this
 * is safe to run against an already-seeded database without touching any
 * value a Super Admin may since have edited.
 */
return new class extends Migration
{
    public function up(): void
    {
        $landingAbout = "VKONNECT is VKollab Technologies' enterprise alumni engagement platform — built to formalize and sustain the organization's relationship with its alumni community for the long term. It brings together verified alumni identity, professional networking, career opportunities, and secure document access in a single governed environment, so the relationship an alumnus built with VKollab continues to grow long after their tenure.";

        $blocks = [
            [
                'landing_hero',
                'Your Career at VKollab Doesn\'t End Here',
                'VKONNECT is the trusted enterprise platform connecting VKollab Technologies alumni for life — lifelong professional networking, career growth opportunities, secure access to your employment documents, and priority visibility into new roles across the organization and its partner network.',
            ],
            [
                'landing_cta',
                'Your VKollab alumni identity, ready when you are.',
                'Join VKONNECT to unlock lifelong networking, career opportunities, and secure access to your documents — all in one trusted enterprise platform.',
            ],
        ];
        foreach ($blocks as [$key, $title, $body]) {
            ContentBlock::firstOrCreate(['key' => $key], ['title' => $title, 'body' => $body]);
        }

        $settings = [
            ['landing_hero_eyebrow', 'VKollab Alumni Network', 'string', 'Hero eyebrow badge text', 'landing'],
            ['landing_cta_primary_label', 'Join VKONNECT', 'string', 'Primary button label (Hero + CTA sections)', 'landing'],
            ['landing_cta_secondary_label', 'Member Login', 'string', 'Secondary button label (Hero + CTA sections)', 'landing'],
            ['landing_about_pillar_1', 'Lifelong alumni engagement, backed by a verified professional identity', 'string', 'About pillar 1', 'landing'],
            ['landing_about_pillar_2', 'Structured professional networking with colleagues across every batch and function', 'string', 'About pillar 2', 'landing'],
            ['landing_about_pillar_3', 'Priority access to career growth opportunities within the organization and its partner network', 'string', 'About pillar 3', 'landing'],
            ['landing_about_pillar_4', 'Secure, self-service access to your employment documents and records', 'string', 'About pillar 4', 'landing'],
            ['footer_copyright_holder', 'VKollab Technologies', 'string', 'Copyright holder name', 'footer'],
        ];
        foreach ($settings as [$key, $value, $type, $label, $group]) {
            Setting::firstOrCreate(['key' => $key], [
                'value' => $value,
                'type' => $type,
                'label' => $label,
                'group' => $group,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        ContentBlock::whereIn('key', ['landing_hero', 'landing_cta'])->delete();
        Setting::whereIn('key', [
            'landing_hero_eyebrow', 'landing_cta_primary_label', 'landing_cta_secondary_label',
            'landing_about_pillar_1', 'landing_about_pillar_2', 'landing_about_pillar_3', 'landing_about_pillar_4',
            'footer_copyright_holder',
        ])->delete();
    }
};
