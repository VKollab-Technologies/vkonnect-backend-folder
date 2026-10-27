<?php

namespace App\Mail;

use App\Services\EmailBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent once when a NEW alumni master record is created — either a single add
 * (Super Admin/HR UI) or a bulk Excel/CSV import. No OTP here; it's purely an
 * invitation to visit the portal and activate the account themselves.
 * Implements ShouldQueue → delivered via the queue worker through Microsoft
 * Graph.
 */
class AlumniRecordWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [30, 120, 300];

    public function __construct(
        public string $name,
        public string $vkollabId,
        public ?string $department = null,
        public ?string $designation = null,
        public ?string $joinedOn = null,
        public ?string $exitedOn = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to VKONNECT Alumni Portal');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.alumni-record-welcome', with: [
            'name' => $this->name,
            'vkollabId' => $this->vkollabId,
            'department' => $this->department,
            'designation' => $this->designation,
            'joinedOn' => $this->joinedOn,
            'exitedOn' => $this->exitedOn,
            'portalUrl' => rtrim(config('app.frontend_url'), '/'),
            'subjectLine' => $this->envelope()->subject,
            'role' => 'alumni',
            ...EmailBranding::values(),
        ]);
    }
}
