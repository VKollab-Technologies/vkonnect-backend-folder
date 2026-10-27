<?php

namespace App\Mail;

use App\Services\EmailBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sends an alumnus their own document as a FILE ATTACHMENT.
 *
 * This replaced an expiring signed link. The link was the safer design — a
 * mailbox copy is permanent, survives every forward and provider backup, and
 * outlives any access control the portal later applies to the same file — but
 * permanence is the requirement: an alumnus keeps the email and re-opens the
 * document years later, with no dependency on a link's TTL, their account
 * still existing, or the portal being reachable at all.
 *
 * Consequences worth knowing, since they are no longer preventable here:
 *   - Alumni Control switches cannot be re-checked when the file is opened. A
 *     revoked download permission stops FUTURE sends, never a delivered
 *     attachment.
 *   - The size guard lives at the send site (DocumentController::emailToSelf),
 *     not here, so an oversized document is refused with a clear message while
 *     the alumnus is still on the page rather than failing silently in a queue.
 */
class DocumentDeliveryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    public function __construct(
        public string $name,
        public string $documentTitle,
        public string $fileName,
        public string $diskPath,
        public ?string $categoryName = null,
        public ?string $financialYear = null,
        public ?string $mimeType = 'application/pdf',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Your document: {$this->documentTitle}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.document-delivery', with: [
            'name' => $this->name,
            'documentTitle' => $this->documentTitle,
            'fileName' => $this->fileName,
            'categoryName' => $this->categoryName,
            'financialYear' => $this->financialYear,
            'subjectLine' => $this->envelope()->subject,
            'role' => 'alumni',
            ...EmailBranding::values(),
        ]);
    }

    /**
     * Attached under the document's ORIGINAL filename, not its storage path:
     * disk paths are opaque generated keys, and an attachment saved as
     * `a3f9c1….pdf` is unfindable in a mailbox three years later.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk(config('filesystems.documents'), $this->diskPath)
                ->as($this->fileName)
                ->withMime($this->mimeType ?? 'application/pdf'),
        ];
    }
}
