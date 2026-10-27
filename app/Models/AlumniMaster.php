<?php

namespace App\Models;

use App\Services\NotificationService;
use App\Services\OtpService;
use App\Support\PanHash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/**
 * Authoritative alumni roster row. Imported from Excel/CSV by the Super Admin;
 * used to validate self-registration by VKollab ID or PAN.
 */
class AlumniMaster extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'vkollab_id',
        'pan',
        'name',
        'email',
        'designation',
        'department',
        'organization',
        'current_designation',
        'phone',
        'location',
        'dob',
        'joined_on',
        'exited_on',
        'last_working_location',
        'band',
        'supervisor_name',
        'supervisor_email',
        'uan_number',
        'pf_number',
        'status',
        'claimed_by',
        'claimed_at',
        'imported_by',
    ];

    protected function casts(): array
    {
        return [
            // Non-deterministic AES via APP_KEY. Uniqueness and lookup do not
            // use this column -- they use pan_hash, kept in step by the
            // saving hook in booted().
            'pan' => 'encrypted',
            'dob' => 'date',
            'joined_on' => 'date',
            'exited_on' => 'date',
            'claimed_at' => 'datetime',
        ];
    }

    /**
     * Employee ID, normalised on the way in — uppercase, trimmed.
     *
     * A mutator rather than each writer remembering: the same
     * `strtoupper(trim())` was spelled out in the single-add controller and in
     * the bulk importer, two copies of one rule with nothing keeping them
     * honest. Registration looks the ID up with an exact match against the
     * NORMALISED input, so a writer that ever forgot would store a row no
     * alumnus could then register against — invisible until someone typed a
     * lowercase ID into the add form.
     *
     * Every path that writes this column now normalises, including seeders,
     * factories, tinker and any future import format.
     */
    protected function vkollabId(): Attribute
    {
        return Attribute::set(fn (?string $value) => self::normalizeVkollabId($value));
    }

    /*
     * PAN normalisation and its blind index both live in a saving hook rather
     * than in an Attribute mutator.
     *
     * `pan` carries an `encrypted` cast, and defining a mutator for the same
     * attribute puts two transformations on one write with the order between
     * them left to the framework. A saving hook runs once, after casting, and
     * says plainly what it does: read the plaintext back, normalise it, write
     * it and its hash together.
     *
     * Every write path is covered -- controller, importer, seeder, factory,
     * tinker -- so pan and pan_hash cannot drift apart. They must not: the
     * unique index is on the hash, so a stale hash would let a duplicate PAN
     * through.
     */
    /**
     * Keep the claimed account's login email in step with the roster.
     *
     * Registration copies `email` into `users` once, at claim time
     * (AuthService::activateFromMaster) — and nothing re-synced it afterwards.
     * Every alumni-facing auth path reads `users.email`: the login OTP, the
     * password-reset code, the masked address shown on screen, and all
     * notifications. So HR correcting a wrong address on the roster changed
     * what HR sees while the alumnus's codes kept going to the old mailbox,
     * with no error anywhere to reveal it.
     *
     * An observer rather than a call in the edit controller, because the roster
     * has more than one writer: the single-record edit, the bulk importer's
     * `update` mode, seeders and tinker. A fix in one of them is a fix that
     * drifts.
     *
     * ONLY email propagates. `name` is deliberately excluded: Alumni Control
     * can edit `users.name` directly, so copying the roster over it would
     * silently undo a deliberate edit. The profile block (phone, designation,
     * department…) is excluded for the same reason. Email is the one field with
     * a single editor and no way for the alumnus to correct it themselves,
     * which is exactly why it must follow the roster.
     */
    /**
     * Roster column => alumni_profiles column, for the HR-managed facts that
     * exist in both tables.
     *
     * `name` and `email` are absent on purpose: they live on `users`, not the
     * profile, and email is handled by syncLoginEmail() with its own
     * uniqueness guard.
     */
    public const PROFILE_FIELD_MAP = [
        // Identity and employment facts the profile also carries. joined_on and
        // exited_on are not cosmetic: they drive the Financial Year options on
        // document upload, so a stale pair offers HR the wrong years to file
        // against.
        'vkollab_id' => 'vkollab_id',
        'pan' => 'pan',
        'joined_on' => 'joined_on',
        'exited_on' => 'exited_on',
        'designation' => 'designation',
        'department' => 'department',
        'organization' => 'current_organization',
        'phone' => 'phone',
        'location' => 'location',
        'last_working_location' => 'last_working_location',
        'band' => 'band',
        'supervisor_name' => 'supervisor_name',
        'supervisor_email' => 'supervisor_email',
        'uan_number' => 'uan_number',
        'pf_number' => 'pf_number',
    ];

    protected static function booted(): void
    {
        // Registered here, not as a bootPanBlindIndex() method: Laravel's
        // boot{Name} convention only auto-runs for traits, so a same-named
        // method on the model itself is never called and the hash would
        // silently never be written.
        static::saving(function (self $model) {
            if (! $model->isDirty('pan')) {
                return;
            }

            $normalised = self::normalizePan($model->pan);
            $model->pan = $normalised;
            $model->pan_hash = PanHash::make($normalised);
        });

        static::updated(function (self $record) {
            if ($record->wasChanged('email')) {
                $record->syncLoginEmail();
            }

            if ($record->wasChanged('vkollab_id')) {
                $record->syncLoginVkollabId();
            }

            if ($record->wasChanged(array_keys(self::PROFILE_FIELD_MAP))) {
                $record->syncClaimedProfile();
            }
        });
    }

    /**
     * Push HR-managed roster facts onto the claimed alumnus's profile.
     *
     * The profile is a snapshot taken at registration
     * (AuthService::activateFromMaster) and, like the email before it, nothing
     * re-synced it. So HR editing band or department on the Alumni Master page
     * saved correctly and then appeared to do nothing, because the alumnus's
     * "My Information" and the Alumni Control dialog both read
     * `alumni_profiles` — a different table from the one that was edited.
     *
     * Only fields the roster actually carries a value for are pushed: a roster
     * row with a blank department must not wipe a department someone filled in
     * on the profile side.
     *
     * @return bool whether anything moved
     */
    public function syncClaimedProfile(): bool
    {
        $profile = $this->claimant?->alumniProfile;

        if (! $profile) {
            return false;
        }

        $changes = [];

        foreach (self::PROFILE_FIELD_MAP as $rosterColumn => $profileColumn) {
            $value = $this->{$rosterColumn};

            if ($value === null || $value === '' || $profile->{$profileColumn} === $value) {
                continue;
            }

            $changes[$profileColumn] = $value;
        }

        if (! $changes) {
            return false;
        }

        $profile->forceFill($changes)->save();

        AuditLog::create([
            'user_id' => $this->claimed_by,
            'actor_name' => $this->claimant?->name,
            'actor_role' => 'alumni',
            'action' => 'alumni.profile_synced_from_roster',
            'module' => 'alumni',
            'subject_type' => self::class,
            'subject_id' => $this->getKey(),
            'meta' => ['fields' => array_keys($changes)],
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Push this record's Employee ID onto the claimed account.
     *
     * `users.vkollab_id` is the login identifier — AuthService resolves it on
     * every sign-in and password reset. Left unsynced, correcting an EMP ID on
     * the roster told the alumnus a new ID that their account did not answer
     * to: they typed it and got "credentials do not match", while the OLD id
     * silently kept working and nobody knew. The profile copy follows via
     * PROFILE_FIELD_MAP.
     *
     * Same collision guard as syncLoginEmail(): users.vkollab_id is unique per
     * role, so a clash means the roster edit is the wrong thing. Leave the
     * working login alone rather than throwing mid-save.
     *
     * @return bool whether the account's identifier moved
     */
    public function syncLoginVkollabId(): bool
    {
        $account = User::find($this->claimed_by);

        if (! $account || $this->vkollab_id === null || $account->vkollab_id === $this->vkollab_id) {
            return false;
        }

        $taken = User::where('role', $account->role)
            ->where('vkollab_id', $this->vkollab_id)
            ->whereKeyNot($account->getKey())
            ->exists();

        if ($taken) {
            Log::warning('Roster EMP ID not propagated to the account: identifier already in use.', [
                'vkollabId' => $this->vkollab_id,
                'userId' => $account->getKey(),
            ]);

            return false;
        }

        $previous = $account->vkollab_id;
        $account->forceFill(['vkollab_id' => $this->vkollab_id])->save();

        AuditLog::create([
            'user_id' => $account->getKey(),
            'actor_name' => $account->name,
            'actor_role' => $account->role,
            'action' => 'alumni.login_id_synced',
            'module' => 'alumni',
            'subject_type' => self::class,
            'subject_id' => $this->getKey(),
            'meta' => ['from' => $previous, 'to' => $this->vkollab_id],
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Push this record's email onto the claimed account's login email.
     *
     * Public and callable on its own so the observer above and the one-off
     * backfill of records that drifted BEFORE the observer existed run the
     * identical code — including the collision guard, which a hand-written
     * repair script would be the obvious place to forget.
     *
     * @return bool whether the account's email actually moved
     */
    public function syncLoginEmail(): bool
    {
        $record = $this;

        if ($record->email === null) {
            return false;
        }

        $account = User::find($record->claimed_by);

        if (! $account || $account->email === $record->email) {
            return false;
        }

        // users.email is unique per role. A collision means the address already
        // belongs to another alumni account, so the roster edit is the thing
        // that is wrong — leave the working login alone rather than throwing
        // mid-request, and let the uniqueness rule on the roster form surface it.
        $taken = User::where('role', $account->role)
            ->where('email', $record->email)
            ->whereKeyNot($account->getKey())
            ->exists();

        if ($taken) {
            Log::warning('Roster email not propagated to the account: address already in use.', [
                'vkollabId' => $record->vkollab_id,
                'userId' => $account->getKey(),
            ]);

            return false;
        }

        // Captured before the write — reading it afterwards returns the new
        // value, which made the audit entry claim the address never moved.
        $previous = $account->email;

        $account->forceFill(['email' => $record->email])->save();

        AuditLog::create([
            'user_id' => $account->getKey(),
            'actor_name' => $account->name,
            'actor_role' => $account->role,
            'action' => 'alumni.login_email_synced',
            'module' => 'alumni',
            'subject_type' => self::class,
            'subject_id' => $record->getKey(),
            // Masked: the audit log is readable by every Super Admin, and the
            // addresses add nothing beyond "it moved".
            'meta' => [
                'from' => OtpService::maskEmail($previous ?? ''),
                'to' => OtpService::maskEmail($record->email),
            ],
            'created_at' => now(),
        ]);

        $this->announceEmailChange($account);

        return true;
    }

    /**
     * Tell the alumnus their login email moved, on both channels.
     *
     * The email goes to the NEW address only, by decision. It confirms the
     * address now works and reaches the mailbox that will receive every future
     * sign-in code.
     *
     * The tradeoff, recorded so it is a choice rather than an oversight: the
     * OLD mailbox is not told. An incorrect or malicious change therefore
     * notifies only whoever now holds the new address, and the person losing
     * access learns nothing. The in-app notice is what covers that gap — an
     * alumnus signed in on another device still sees it without needing either
     * mailbox — which is why it is not optional here.
     *
     * Never allowed to throw. This runs inside a roster save, and a mail
     * failure must not undo an email change already written and audited — that
     * would leave the login moved while the record claimed it had not.
     */
    private function announceEmailChange(User $account): void
    {
        $changedBy = match (auth()->user()?->role) {
            'super_admin' => 'a Super Admin',
            'hr_admin' => 'the HR team',
            default => 'an administrator',
        };

        // "set to this address" rather than naming it: the recipient IS the new
        // address, so quoting it back reads as a template that forgot to fill in.
        $body = "Your VKONNECT login email has been set to this address by {$changedBy}. "
            .'Sign-in codes and notifications will now come here. '
            ."If you didn't request this change, contact HR immediately.";

        $notifications = app(NotificationService::class);

        try {
            $notifications->email(
                $this->email,
                'Your VKONNECT login email was changed',
                'Login email changed',
                $body,
                null,
                null,
                'alumni',
            );

            $notifications->notify($account->getKey(), 'system', 'Your login email was changed', $body);
        } catch (\Throwable $e) {
            Log::warning('Login email change notice failed to send: '.$e->getMessage(), [
                'vkollabId' => $this->vkollab_id,
            ]);
        }
    }

    /** The user account that registered against this record (if claimed). */
    public function claimant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    /**
     * Whether a LIVE account holds this record.
     *
     * A claim whose account has since been deleted does not count: the point
     * of the flag is "someone is using this record", and a deleted account
     * isn't. Checking the claimant as well as the timestamp means rows that
     * were orphaned before AlumniAccountService::destroy released claims
     * (see there) heal themselves instead of staying permanently locked.
     */
    public function isClaimed(): bool
    {
        if ($this->claimed_at === null) {
            return false;
        }

        // claimed_at set but no claimant id — treat as unclaimed rather than
        // trusting a dangling timestamp.
        if ($this->claimed_by === null) {
            return false;
        }

        // Prefer the eager-loaded relation. Without this the resource runs one
        // EXISTS per row, so a 100-row roster page fired 100 extra queries.
        // Callers that paginate should ->with('claimant'); the query below is
        // the fallback for a single model loaded on its own.
        if ($this->relationLoaded('claimant')) {
            return $this->claimant !== null;
        }

        return User::whereKey($this->claimed_by)->exists();
    }

    /**
     * The SQL form of isClaimed(), for filtering and counting.
     *
     * whereHas('claimant') carries User's SoftDeletingScope, so a claim held
     * by a deleted account is excluded here exactly as isClaimed() excludes
     * it. Filtering on raw claimed_at instead — which is what the roster list
     * and its summary counts did — made an orphaned row display as unclaimed
     * while being counted as claimed and hidden from the "unclaimed" filter.
     */
    public function scopeClaimed(Builder $query): Builder
    {
        return $query->whereNotNull('claimed_at')
            ->whereNotNull('claimed_by')
            ->whereHas('claimant');
    }

    public function scopeUnclaimed(Builder $query): Builder
    {
        return $query->where(fn (Builder $q) => $q
            ->whereNull('claimed_at')
            ->orWhereNull('claimed_by')
            ->orWhereDoesntHave('claimant'));
    }

    /** Normalise a PAN for storage/lookup (uppercase, trimmed). */
    public static function normalizePan(?string $pan): ?string
    {
        $pan = trim((string) $pan);

        return $pan === '' ? null : strtoupper($pan);
    }

    /** Normalise an employee id for storage/lookup (uppercase, trimmed). */
    public static function normalizeVkollabId(?string $id): ?string
    {
        $id = trim((string) $id);

        return $id === '' ? null : strtoupper($id);
    }
}
