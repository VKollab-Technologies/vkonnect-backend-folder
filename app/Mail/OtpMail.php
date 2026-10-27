<?php

namespace App\Mail;

use App\Services\EmailBranding;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * One-time-password email (registration/activation, password reset, and
 * HR/Super-Admin login 2FA). Implements ShouldQueue → pushed to the queue and
 * delivered by a worker via Microsoft Graph. Both brand logos (referenced via
 * `cid:vkollab-logo`/`cid:vkonnect-logo` in the shared layout) are embedded by
 * MicrosoftGraphTransport at send-time, not here — see that class for why.
 */
class OtpMail extends Mailable 
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [30, 120, 300];

    public function __construct(
        public string $code,
        public string $purpose,
        public int $ttlMinutes = 10,
        public ?string $name = null,
        public ?string $role = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: match ($this->purpose) {
            'register' => 'VKONNECT Account Verification OTP',
            'reset' => 'VKONNECT Password Reset OTP',
            'login' => 'Your VKONNECT Login Verification Code',
            default => 'Your VKONNECT verification code',
        });
    }

    public function content(): Content
    {
        [$heading, $action] = match ($this->purpose) {
            'register' => ['Welcome to VKONNECT', 'verify your VKONNECT account and activate your alumni profile'],
            'reset' => ['Reset your password', 'reset your VKONNECT password'],
            'login' => ['Verify it\'s you', 'sign in to your VKONNECT account'],
            default => ['Verification code', 'continue'],
        };

        return new Content(view: 'emails.otp', with: [
            'code' => $this->code,
            'action' => $action,
            'heading' => $heading,
            'ttl' => $this->ttlMinutes,
            'name' => $this->name,
            'role' => $this->role,
            'subjectLine' => $this->envelope()->subject,
            ...EmailBranding::values(),
        ]);
    }
}
