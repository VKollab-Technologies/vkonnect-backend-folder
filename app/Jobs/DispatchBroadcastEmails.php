<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Fans a broadcast email out to every eligible active user in the given
 * role(s) — moved off the request thread (was
 * NotificationService::emailRoles() looping synchronously) so a "create job
 * posting"/"publish announcement" request doesn't block on N sequential
 * mail-queue pushes + audit writes as the alumni base grows. Requires a
 * running queue worker (`php artisan queue:work`) in every environment —
 * without one, job/announcement broadcast emails simply never send (the
 * `composer dev` script already starts one for local dev; the `deploy`
 * composer script does not, so a persistent worker process must be
 * provisioned separately in staging/production).
 */
class DispatchBroadcastEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries = 3;

    public function __construct(
        public array $roles,
        public string $subject,
        public string $heading,
        public string $body,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null,
        /**
         * Who asked for this broadcast, captured at dispatch time.
         *
         * A queued job has no authenticated user, so failed() cannot look the
         * initiator up — without this the person who published the
         * announcement is unreachable, which is precisely the case this class
         * used to fail silently in.
         */
        public ?string $initiatedBy = null,
    ) {}

    public function handle(NotificationService $notifications): void
    {
        $recipients = User::whereIn('role', $this->roles)
            ->where('status', 'active')
            ->whereNull('notifications_disabled_at')
            ->pluck('email');

        // Only meaningful when broadcasting to a single realm — ambiguous
        // for a mixed-role fan-out, so the badge falls back to the default.
        $role = count($this->roles) === 1 ? $this->roles[0] : null;

        foreach ($recipients as $to) {
            $notifications->email($to, $this->subject, $this->heading, $this->body, $this->actionUrl, $this->actionLabel, $role);
        }
    }

    /**
     * Final failure, after every retry is spent.
     *
     * Without this the job landed in `failed_jobs` and stopped there: no audit
     * entry, nothing in the UI, and an HR admin who broadcast to the entire
     * alumni base had no way to learn it never sent. A broadcast that silently
     * does not go out is the worst failure mode in the app, because the sender
     * believes the opposite.
     *
     * Both halves are defensive — this runs on a worker with no request
     * context, and an exception thrown from failed() is swallowed by the
     * queue, so a mistake here would restore the silence it exists to remove.
     */
    public function failed(?Throwable $e): void
    {
        $recipientCount = User::whereIn('role', $this->roles)
            ->where('status', 'active')
            ->whereNull('notifications_disabled_at')
            ->count();

        $reason = $e?->getMessage() ?? 'Unknown error.';

        AuditLog::create([
            'user_id' => $this->initiatedBy,
            'actor_name' => $this->initiatedBy ? User::find($this->initiatedBy)?->name : null,
            'actor_role' => $this->initiatedBy ? User::find($this->initiatedBy)?->role : null,
            'action' => 'broadcast.failed',
            'status' => 'failure',
            'module' => 'notifications',
            'meta' => [
                'subject' => $this->subject,
                'roles' => $this->roles,
                'recipientCount' => $recipientCount,
                'attempts' => $this->tries,
                // Truncated: a stack-trace-length message would bloat every
                // audit-log page that renders this row.
                'error' => mb_substr($reason, 0, 300),
            ],
            'created_at' => now(),
        ]);

        Log::error('Broadcast email dispatch failed', [
            'subject' => $this->subject,
            'roles' => $this->roles,
            'recipientCount' => $recipientCount,
            'initiatedBy' => $this->initiatedBy,
            'error' => $reason,
        ]);

        if (! $this->initiatedBy) {
            return;
        }

        // In-app rather than email: the failure being reported IS a mail
        // failure, so emailing the news of it is the least reliable channel
        // available.
        app(NotificationService::class)->notify(
            $this->initiatedBy,
            'system',
            'Your broadcast could not be sent',
            "\"{$this->subject}\" was not delivered to {$recipientCount} recipient(s) after {$this->tries} attempts. "
                .'No email was sent. Please try again, or contact support if this continues.',
        );
    }
}
