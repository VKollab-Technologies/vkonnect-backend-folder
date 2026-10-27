<?php

namespace App\Services;

use App\Jobs\DispatchBroadcastEmails;
use App\Mail\AdminWelcomeMail;
use App\Mail\NotificationMail;
use App\Models\AppNotification;
use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Creates in-app notifications (MySQL). Jobs, announcements, documents, and
 * ticker alerts are in-app-only by default via notifyRoles(). A real email is
 * ADDITIONALLY sent for new Jobs and Announcements via emailRoles(), but only
 * when the Super-Admin-controlled `email_broadcasts_enabled` setting is on —
 * this is on top of, not instead of, the in-app notification. Email delivery
 * (Microsoft Graph) is also used for account-activation OTP, forgot-password
 * OTP, welcome, login OTP, and the account-approved transactional email, all
 * sent via email() from their own controllers/services with a DB-sourced
 * recipient.
 */
class NotificationService
{
    public function notify(string $userId, string $type, string $title, ?string $body = null, ?string $actionUrl = null): ?AppNotification
    {
        if (User::whereKey($userId)->whereNotNull('notifications_disabled_at')->exists()) {
            return null;
        }

        $notification = AppNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
        ]);

        AuditLogger::log('notification.sent', 'notifications', $notification->id, [
            'type' => $type,
            'title' => $title,
            'recipients' => 1,
        ]);

        return $notification;
    }

    /** Fan out an in-app-only notification to every active user matching the role(s). */
    public function notifyRoles(array $roles, string $type, string $title, ?string $body = null, ?string $actionUrl = null): int
    {
        $now = Carbon::now();
        $userIds = User::whereIn('role', $roles)->where('status', 'active')
            ->whereNull('notifications_disabled_at')
            ->pluck('id');

        $rows = $userIds->map(fn ($id) => [
            'id' => Str::uuid()->toString(),
            'user_id' => $id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        if ($rows) {
            AppNotification::insert($rows);

            AuditLogger::log('notification.sent', 'notifications', null, [
                'type' => $type,
                'title' => $title,
                'roles' => $roles,
                'recipients' => count($rows),
            ]);
        }

        return count($rows);
    }

    /** Queue a single transactional email (account activation, password reset, welcome, approval) to one DB-sourced recipient. */
    public function email(string $to, string $subject, string $heading, string $body, ?string $actionUrl = null, ?string $actionLabel = null, ?string $role = null): void
    {
        try {
            Mail::to($to)->queue(new NotificationMail($subject, $heading, $body, $actionUrl, $actionLabel, $role));

            AuditLog::create([
                'action' => 'email.notification_sent',
                'status' => 'success',
                'module' => 'notifications',
                'meta' => ['to' => OtpService::maskEmail($to), 'subject' => $subject],
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification mail dispatch failed: '.$e->getMessage());

            AuditLog::create([
                'action' => 'email.notification_sent',
                'status' => 'failure',
                'module' => 'notifications',
                'meta' => ['to' => OtpService::maskEmail($to), 'subject' => $subject, 'error' => $e->getMessage()],
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Welcome a newly provisioned HR admin or Super Admin with their EMP ID,
     * temporary password and a link to the right sign-in page.
     *
     * Shared by both provisioning controllers rather than duplicated per realm
     * — the mailable already handles the realm differences. Failures are
     * swallowed and audited for the same reason the other senders do it: the
     * account has been created and committed by this point, and throwing here
     * would surface as a failed provisioning request for an admin who actually
     * exists. The audit row is what tells you to resend.
     *
     * @param  'hr_admin'|'super_admin'  $role
     */
    public function adminWelcome(
        User $admin,
        string $temporaryPassword,
        string $role,
        ?string $department = null,
        ?string $invitedBy = null,
    ): void {
        try {
            Mail::to($admin->email)->queue(new AdminWelcomeMail(
                name: $admin->name,
                vkollabId: (string) $admin->vkollab_id,
                email: $admin->email,
                temporaryPassword: $temporaryPassword,
                role: $role,
                department: $department,
                invitedBy: $invitedBy,
            ));

            AuditLog::create([
                'action' => 'email.admin_welcome_sent',
                'status' => 'success',
                'module' => $role === 'super_admin' ? 'platform' : 'hr',
                // Never the password, obviously — this row is read by anyone
                // with audit-log access.
                'meta' => ['to' => OtpService::maskEmail($admin->email), 'role' => $role],
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Admin welcome mail dispatch failed: '.$e->getMessage());

            AuditLog::create([
                'action' => 'email.admin_welcome_sent',
                'status' => 'failure',
                'module' => $role === 'super_admin' ? 'platform' : 'hr',
                'meta' => ['to' => OtpService::maskEmail($admin->email), 'role' => $role, 'error' => $e->getMessage()],
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Additionally email every eligible active user of the given role(s) —
     * used for New Job and Announcement broadcasts. No-ops entirely unless
     * the `email_broadcasts_enabled` platform setting is on. The actual
     * per-recipient fan-out (recipient resolution + send + audit) runs on the
     * queue via DispatchBroadcastEmails, not in the request — see that job
     * for the notifications-disabled-flag filtering and audit behavior,
     * which is unchanged from before this was queued.
     */
    public function emailRoles(array $roles, string $subject, string $heading, string $body, ?string $actionUrl = null, ?string $actionLabel = null): void
    {
        if (! Setting::find('email_broadcasts_enabled')?->typedValue()) {
            return;
        }

        // Captured here, in the request, because the job runs without an
        // authenticated user and needs someone to report a failure back to.
        DispatchBroadcastEmails::dispatch(
            $roles, $subject, $heading, $body, $actionUrl, $actionLabel,
            auth()->id(),
        );
    }
}
