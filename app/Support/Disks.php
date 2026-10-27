<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * Resolves storage disks by purpose instead of by name.
 *
 * Call sites used to say Storage::disk('local') in 24 places, which meant
 * moving to S3 was a code change rather than a config change — the one thing
 * still blocking that, since queue, cache and session are already fully
 * environment-driven. Routing through here leaves a single place to change.
 *
 * @see config/filesystems.php for why imports() is deliberately not remote.
 */
final class Disks
{
    /** Documents, avatars, resumes — anything a user stored. Safe to move to S3. */
    public static function documents(): Filesystem
    {
        return Storage::disk(config('filesystems.documents'));
    }

    /** Import staging only. Local by design: the spreadsheet reader needs a real path. */
    public static function imports(): Filesystem
    {
        return Storage::disk(config('filesystems.imports'));
    }
}
