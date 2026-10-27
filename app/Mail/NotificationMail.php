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
 * Generic transactional/notification email (account approved, job/announcement
 * broadcasts, platform notices). Queued for delivery by `queue:work`, which
 * reads the `database` queue connection.
 */
class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [30, 120, 300];

    public function __construct(
        public string $subjectLine,
        public string $heading,
        public string $body,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null,
        public ?string $role = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectLine);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.notification', with: [
            'heading' => $this->heading,
            'body' => $this->body,
            'actionUrl' => $this->actionUrl,
            'actionLabel' => $this->actionLabel,
            'role' => $this->role,
            ...EmailBranding::values(),
        ]);
    }
}
