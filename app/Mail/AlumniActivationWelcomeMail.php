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
 * Sent exactly ONCE, immediately after an alumnus verifies their OTP and
 * creates a password (RegisterController::setPassword) — never resent. This
 * is distinct from AlumniRecordWelcomeMail (the earlier invitation sent when
 * HR/Super Admin first added the record): this one confirms activation is
 * complete, shows the alumni's profile snapshot, and links straight to login.
 */
class AlumniActivationWelcomeMail extends Mailable implements ShouldQueue
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
        return new Content(view: 'emails.alumni-activation-welcome', with: [
            'name' => $this->name,
            'vkollabId' => $this->vkollabId,
            'department' => $this->department,
            'designation' => $this->designation,
            'joinedOn' => $this->joinedOn,
            'exitedOn' => $this->exitedOn,
            'loginUrl' => rtrim(config('app.frontend_url'), '/').'/alumni/login',
            'subjectLine' => $this->envelope()->subject,
            'role' => 'alumni',
            ...EmailBranding::values(),
        ]);
    }
}
