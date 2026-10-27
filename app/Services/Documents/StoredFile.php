<?php

namespace App\Services\Documents;

/**
 * A file that has been written to the document disk.
 *
 * Replaces the `array{path:string, name:string}` the storage helper used to
 * return, which forced every caller to remember two string keys and then
 * separately re-derive the size and MIME type from the UploadedFile. Both
 * call sites did that derivation independently — including the
 * `?: 1` guard for sub-1 KB files — so a change to either rule had to be made
 * twice and was silently wrong if it wasn't.
 */
final readonly class StoredFile
{
    public function __construct(
        /** Path on the private disk, as returned by putFileAs(). */
        public string $path,
        /** The generated EMPID_DOCUMENTCODE_YEAR name, never the uploaded one. */
        public string $name,
        public string $mimeType,
        public int $sizeKb,
    ) {}

    /**
     * The document columns this file determines.
     *
     * Kept here so store() and replace() cannot drift apart on which fields a
     * new file updates.
     *
     * @return array<string, string|int>
     */
    public function toAttributes(): array
    {
        return [
            'disk_path' => $this->path,
            'original_name' => $this->name,
            'mime_type' => $this->mimeType,
            'size_kb' => $this->sizeKb,
        ];
    }
}
