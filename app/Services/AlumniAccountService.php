<?php

namespace App\Services;

use App\Models\AlumniMaster;
use App\Models\Document;
use App\Models\User;
use App\Support\Disks;
use App\Support\PasswordReset;
use Illuminate\Support\Str;

/**
 * Shared alumni-account-management operations used by both the HR and Super
 * Admin "Alumni Control" controllers. Parameterized by $actorRealm
 * ('hr_admin' | 'super_admin') so notification/audit context can differ
 * without duplicating the surrounding logic.
 *
 * Token revocation on disable/delete happens for both realms — a disabled or
 * deleted alumnus must not be able to keep using an existing (non-expiring)
 * Sanctum token regardless of which realm disabled them. The super_admin
 * notification fan-out remains HR-only; that's a business/communication
 * choice (HR keeps Super Admin informed of actions Super Admin didn't take
 * themselves), not a security control, so it's untouched.
 */
class AlumniAccountService
{
    public function __construct(private readonly NotificationService $notifications) {}

    /**
     * The Alumni Control switch set, as the API speaks it.
     *
     * Derived from User::ALUMNI_CAPABILITY_COLUMNS so a new alumni feature is
     * declared exactly once. `documents` becomes `documentsEnabled` in the
     * payload and `alumni.disable_documents` as the permission gating who may
     * flip it — both mechanical, both previously retyped by hand in two
     * controllers that then drifted apart.
     *
     * @return array<string, array{capability: string, column: string, permission: string}>
     */
    public static function controlMap(): array
    {
        $map = [];

        foreach (User::ALUMNI_CAPABILITY_COLUMNS as $capability => $column) {
            $map[Str::camel($capability).'Enabled'] = [
                'capability' => $capability,
                'column' => $column,
                'permission' => "alumni.disable_{$capability}",
            ];
        }

        return $map;
    }

    /**
     * Apply a partial set of Alumni Control switches.
     *
     * Each field present in the payload is checked against its own permission
     * and recorded individually, so an admin who may control documents but not
     * jobs can still send a documents-only update.
     *
     * @param  array<string, bool>  $data  validated `<name>Enabled` => bool
     */
    public function updateControls(User $alumnus, array $data, User $actor): void
    {
        $map = self::controlMap();
        $changes = [];

        foreach ($data as $field => $enabled) {
            $meta = $map[$field];

            abort_unless(
                $actor->can($meta['permission']),
                403,
                "You do not have permission to control {$meta['capability']} access.",
            );

            $changes[$meta['column']] = $enabled ? null : now();
        }

        if (! $changes) {
            return;
        }

        // No token revocation here, deliberately: enforcement is server-side
        // and takes effect on the alumnus's very next request, and the frontend
        // re-reads capabilities from /auth/me on an interval. Signing someone
        // out of the whole portal because HR adjusted one switch would be a far
        // bigger consequence than the action implies.
        $alumnus->forceFill($changes)->save();

        AuditLogger::log('alumni.controls_updated', 'alumni', $alumnus->id, [
            'alumnus' => $alumnus->name,
            'changes' => $data,
        ]);
    }

    /** Activate / disable an alumnus account. */
    public function updateStatus(User $alumnus, string $status, string $actorRealm, string $actorName): void
    {
        $alumnus->update(['status' => $status]);

        if ($status === 'disabled') {
            $alumnus->tokens()->delete();
        }

        AuditLogger::log('alumni.status_changed', 'alumni', $alumnus->id, [
            'alumnus' => $alumnus->name,
            'status' => $alumnus->status,
        ]);

        if ($actorRealm === 'hr_admin') {
            $actionVerb = $status === 'active' ? 'activated' : 'disabled';
            $this->notifications->notifyRoles(
                ['super_admin'],
                'alumni',
                $status === 'active' ? 'Alumni Activated' : 'Alumni Disabled',
                "{$actorName} {$actionVerb} {$alumnus->name}'s account.",
                '/super-admin/alumni',
            );
        }
    }

    /** Lock / unlock an alumnus account. Identical in both realms. */
    public function updateLock(User $alumnus, bool $locked): void
    {
        $alumnus->forceFill(['locked_until' => $locked ? now()->addYears(100) : null])->save();

        if ($locked) {
            $alumnus->tokens()->delete();
        }

        AuditLogger::log($locked ? 'alumni.locked' : 'alumni.unlocked', 'alumni', $alumnus->id, [
            'alumnus' => $alumnus->name,
        ]);
    }

    /**
     * Reset an alumnus's password. Never generates or transmits a plaintext
     * password — revokes all active sessions and points them to the existing
     * self-service Forgot Password (OTP) flow. Only the notification copy
     * differs by actor realm.
     */
    public function resetPassword(User $alumnus, string $actorRealm): void
    {
        // Actually invalidate the credential. This previously revoked sessions
        // and emailed "your password was reset" while leaving the old password
        // fully working — so an admin responding to a compromise believed they
        // had killed the credential when they had only killed the sessions,
        // and the email stated something untrue.
        //
        // The replacement is random and is never transmitted or stored
        // anywhere in plaintext: nobody knows it, which is the point. Password
        // sign-in is dead until the owner sets a new one through the OTP-backed
        // Forgot Password flow the email already directs them to.
        PasswordReset::scramble($alumnus);

        [$heading, $body] = $actorRealm === 'hr_admin'
            ? ['Password reset by HR', "HR has reset your password on VKONNECT. Please use the 'Forgot Password' option on the sign-in page with your EMP ID to set a new password."]
            : ['Password reset by Super Admin', "A platform administrator has reset your password on VKONNECT. Please use the 'Forgot Password' option on the sign-in page with your EMP ID to set a new password."];

        $this->notifications->email(
            $alumnus->email,
            'Your VKONNECT password was reset',
            $heading,
            $body,
        );

        AuditLogger::log('alumni.password_reset', 'alumni', $alumnus->id, ['alumnus' => $alumnus->name]);
    }

    /** Soft-delete an alumnus. */
    public function destroy(User $alumnus, string $actorRealm, string $actorName): void
    {
        $name = $alumnus->name;

        $alumnus->tokens()->delete();

        // Release the roster claim before the account goes.
        //
        // Without this the alumni_masters row keeps claimed_by / claimed_at
        // pointing at a deleted account, so it still reports isClaimed() and
        // the roster refuses to delete it ("claimed by a registered account").
        // That left the record stuck: gone from Alumni Control, undeletable in
        // Alumni Master, and un-registerable because the EMP ID was taken.
        AlumniMaster::where('claimed_by', $alumnus->id)
            ->update(['claimed_by' => null, 'claimed_at' => null]);

        // Remove the vault files. Deleting the account leaves its documents
        // rows pointing at a soft-deleted user, so nothing in the app can ever
        // reach those files again — they would sit on disk permanently, which
        // is both a storage leak and personal data (PAN, payslips) retained
        // past the point the account was removed.
        //
        // Rows are soft-deleted alongside so the audit trail still shows what
        // existed, and so the prune command does not later report them as
        // orphans it should clean up again.
        $documents = Document::where('user_id', $alumnus->id)->get();
        foreach ($documents as $document) {
            if ($document->disk_path) {
                Disks::documents()->delete($document->disk_path);
            }
        }
        Document::where('user_id', $alumnus->id)->delete();

        $alumnus->delete();

        AuditLogger::log('alumni.deleted', 'alumni', $alumnus->id, [
            'alumnus' => $name,
            'documentsRemoved' => $documents->count(),
        ]);

        if ($actorRealm === 'hr_admin') {
            $this->notifications->notifyRoles(
                ['super_admin'],
                'alumni',
                'Alumni Account Deleted',
                "{$actorName} deleted {$name}'s account.",
                '/super-admin/alumni',
            );
        }
    }
}
