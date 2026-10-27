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
 * Sent exactly ONCE, when a Super Admin provisions an HR admin or another
 * Super Admin — the admin-side counterpart to AlumniActivationWelcomeMail.
 *
 * One class covers both realms because the only differences are the badge, the
 * login path and a line of copy; the layout is already role-aware.
 *
 * This mail carries the temporary password in plaintext, which is the one
 * place in the codebase that does so (AlumniAccountService::resetPassword
 * deliberately does not — it points people at the OTP flow instead). The
 * difference is that here the account did not exist a moment ago: there is no
 * established channel to the new admin yet, and the provisioning form already
 * asks the creating admin to type a temporary password. The mitigations are
 * that the password is temporary by construction and the mail tells them to
 * change it immediately. If you later add a first-login password-change
 * requirement, this is the mail that should stop carrying the password.
 */
class AdminWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    /** @param  'hr_admin'|'super_admin'  $role */
    public function __construct(
        public string $name,
        public string $vkollabId,
        public string $email,
        public string $temporaryPassword,
        public string $role,
        public ?string $department = null,
        public ?string $invitedBy = null,
    ) {}

    public function envelope(): Envelope
    {
        $platform = EmailBranding::values()['platformName'];

        return new Envelope(
            subject: $this->role === 'super_admin'
                ? "Your {$platform} Super Admin account is ready"
                : "Your {$platform} HR Admin account is ready",
        );
    }

    public function content(): Content
    {
        $base = rtrim(config('app.frontend_url'), '/');

        return new Content(view: 'emails.admin-welcome', with: [
            'name' => $this->name,
            'vkollabId' => $this->vkollabId,
            'email' => $this->email,
            'temporaryPassword' => $this->temporaryPassword,
            'department' => $this->department,
            'invitedBy' => $this->invitedBy,
            'roleLabel' => $this->role === 'super_admin' ? 'Super Admin' : 'HR Admin',
            // Realm-specific sign-in pages — sending an HR admin to the alumni
            // login would reject their credentials with no useful explanation.
            'loginUrl' => $base.($this->role === 'super_admin' ? '/super-admin/login' : '/hr/login'),
            'subjectLine' => $this->envelope()->subject,
            'role' => $this->role,
            ...EmailBranding::values(),
        ]);
    }
}
