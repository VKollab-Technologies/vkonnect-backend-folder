<?php

use App\Models\Setting;
use App\Support\PlatformFeatures;
use Illuminate\Database\Migrations\Migration;

/**
 * Regroups the "send document to email" flag out of Access.
 *
 * The control now lives on the Documents page itself rather than in Platform
 * Settings — it governs one document action, and an admin looking for it went
 * to Documents first. The row keeps its value; only `group` changes, which is
 * what moves it out of the Settings page (that page skips this group).
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::where('key', PlatformFeatures::DOCUMENT_EMAIL)->update([
            'group' => 'documents',
            'label' => 'Let alumni email their own documents to themselves',
        ]);
    }

    public function down(): void
    {
        Setting::where('key', PlatformFeatures::DOCUMENT_EMAIL)->update(['group' => 'access']);
    }
};
