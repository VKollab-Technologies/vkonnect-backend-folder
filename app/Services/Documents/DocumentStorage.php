<?php

namespace App\Services\Documents;

use App\Models\DocumentCategory;
use App\Support\DocumentFilename;
use Illuminate\Http\UploadedFile;
use App\Support\Disks;

/**
 * Everything the document feature does to the filesystem, in one place.
 *
 * This was a private method on Hr\DocumentController, which meant the disk
 * layout, the naming convention and the collision rule were only reachable
 * from HR's controller — and the alumni and Super Admin paths that touch the
 * same files had to re-implement or bypass them. Sitting in a service, the
 * rules are testable on their own and the controller goes back to
 * validate → delegate → respond.
 *
 * Files live on the private documents disk (filesystems.documents). Nothing
 * here is web-accessible; reads go through signed stream routes.
 */
class DocumentStorage
{

    /**
     * Write an upload to the owner's folder under the
     * EMPID_DOCUMENTCODE_YEAR convention (App\Support\DocumentFilename), which
     * names both the path and the filename served back on view/download. The
     * uploader's own filename is never used.
     *
     * A numeric suffix is appended only when that exact name already exists in
     * the folder — two Form 16 uploads for the same alumnus and year, say.
     *
     * @param  string  $folderKey  Owner identity: a user id, or "roster/{id}"
     *                             for a document filed before activation. Keyed
     *                             by owner so a hand-off never has to move files.
     * @param  string|null  $ignorePath  A document's own current file, excluded
     *                                   from the collision check when replacing
     *                                   it — otherwise every replace "collides"
     *                                   with the file it is about to overwrite
     *                                   and gains a spurious _2.
     */
    public function put(
        UploadedFile $file,
        string $folderKey,
        ?string $vkollabId,
        ?DocumentCategory $category,
        ?string $financialYear,
        ?string $ignorePath = null,
        ?string $documentName = null,
        ?int $periodMonth = null,
    ): StoredFile {
        $folder = "documents/{$folderKey}";
        $base = DocumentFilename::buildFor($vkollabId, $category, $financialYear, $documentName, $periodMonth);
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'pdf';

        $taken = fn (string $path) => $path !== $ignorePath && Disks::documents()->exists($path);

        $name = "{$base}.{$extension}";
        for ($suffix = 2; $taken("{$folder}/{$name}"); $suffix++) {
            $name = "{$base}_{$suffix}.{$extension}";
        }

        return new StoredFile(
            path: Disks::documents()->putFileAs($folder, $file, $name),
            name: $name,
            mimeType: $file->getClientMimeType(),
            // Round up, but never report 0 KB: a sub-1 KB file is still a file,
            // and a 0 in the UI reads as a failed upload.
            sizeKb: (int) ceil($file->getSize() / 1024) ?: 1,
        );
    }

    /** Remove a file, tolerating a path that is already gone. */
    public function delete(?string $path): void
    {
        if ($path) {
            Disks::documents()->delete($path);
        }
    }

    /**
     * Delete the file a document is moving away from.
     *
     * No-ops when the replacement landed on the same path — it has already
     * overwritten the old bytes, and deleting would remove the new file.
     */
    public function deleteReplaced(?string $oldPath, StoredFile $replacement): void
    {
        if ($oldPath && $oldPath !== $replacement->path) {
            $this->delete($oldPath);
        }
    }
}
