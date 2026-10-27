<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * VKONNECT user — the shared identity across all three realms. The `role`
 * column distinguishes realm (super_admin | hr_admin | alumni).
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'vkollab_id',
        'password',
        'role',
        'status',
        'avatar_path',
        'last_login_at',
        'last_login_ip',
        'locked_until',
        'failed_login_attempts',
        'permissions_customized_at',
        // Capability columns are appended in boot() from the two capability
        // maps, so a new switch never has to be remembered here as well.
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Every capability column across both realms, alumni first.
     *
     * array_values() is applied to each map BEFORE merging, not after. The two
     * maps are keyed by capability name and share five of them (documents,
     * jobs, applications, ticker, announcements) — merging the associative
     * arrays let the HR column overwrite the alumni one at each shared key,
     * silently yielding 12 columns instead of 17 and dropping five alumni
     * columns out of $fillable and $casts.
     */
    public static function capabilityColumns(): array
    {
        return array_merge(
            array_values(self::ALUMNI_CAPABILITY_COLUMNS),
            array_values(self::HR_CAPABILITY_COLUMNS),
        );
    }

    protected function casts(): array
    {
        return array_merge(
            [
                'email_verified_at' => 'datetime',
                'last_login_at' => 'datetime',
                'locked_until' => 'datetime',
                'permissions_customized_at' => 'datetime',
                'password' => 'hashed',
            ],
            // Same reasoning as $fillable: derive, don't retype. Before this,
            // profile_disabled_at was missing from both lists and only worked
            // because every writer happened to use forceFill().
            array_fill_keys(self::capabilityColumns(), 'datetime'),
        );
    }

    public function __construct(array $attributes = [])
    {
        $this->mergeFillable(self::capabilityColumns());

        parent::__construct($attributes);
    }

    /**
     * Look a user up by EMP ID, case- and whitespace-insensitively.
     *
     * Roster rows are normalised to uppercase on import
     * (AlumniMaster::normalizeVkollabId) and alumni inherit that at
     * activation, but HR/Super Admin EMP IDs are stored exactly as the
     * provisioning admin typed them ("Hr1001"). So neither a raw comparison
     * nor forcing uppercase is correct for everyone: the first rejects
     * " alm1001 " for a registered alumnus — which then fell through to the
     * roster probe and told them to go and register — and the second would
     * lock out every admin whose ID was not typed in caps.
     *
     * UPPER() on the column means this cannot use the vkollab_id index. The
     * users table is small and these are login-path lookups, so correctness
     * wins; revisit if the table ever grows into six figures.
     */
    public function scopeWhereVkollabId(Builder $query, ?string $vkollabId): Builder
    {
        return $query->whereRaw('UPPER(TRIM(vkollab_id)) = ?', [strtoupper(trim((string) $vkollabId))]);
    }

    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class);
    }

    public function hrAdmin(): HasOne
    {
        return $this->hasOne(HrAdmin::class);
    }

    /** Whether the account is currently locked out. */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    /**
     * HR "Alumni Control" switches — one row per alumni-facing feature.
     *
     * Single source of truth, deliberately. This map used to be retyped by
     * hand in five places (two controllers, the middleware, this model's
     * accessors, and UserResource); the copies drifted, and features that were
     * never added to one of them ended up switchable in the UI but enforced
     * nowhere. Everything now derives from here — add a feature once and the
     * payload, the middleware and both controllers pick it up.
     */
    public const ALUMNI_CAPABILITY_COLUMNS = [
        'documents' => 'documents_disabled_at',
        'downloads' => 'downloads_disabled_at',
        'jobs' => 'jobs_disabled_at',
        'applications' => 'applications_disabled_at',
        'notifications' => 'notifications_disabled_at',
        'ticker' => 'ticker_disabled_at',
        'profile' => 'profile_disabled_at',
        'announcements' => 'announcements_disabled_at',
        'dashboard' => 'dashboard_disabled_at',
        'service_requests' => 'service_requests_disabled_at',
    ];

    /**
     * Whether an alumni capability is switched on for this alumnus. Returns
     * true unless HR or a Super Admin has explicitly disabled it — role-level
     * permissions still apply on top (both must pass).
     */
    public function alumniCan(string $capability): bool
    {
        $column = self::ALUMNI_CAPABILITY_COLUMNS[$capability] ?? null;

        if ($column === null || $this->role !== 'alumni') {
            return true;
        }

        return $this->{$column} === null;
    }

    /** All alumni capability states, for the API payload. */
    public function alumniCapabilities(): array
    {
        if ($this->role !== 'alumni') {
            return [];
        }

        return collect(self::ALUMNI_CAPABILITY_COLUMNS)
            ->map(fn (string $column) => $this->{$column} === null)
            ->all();
    }

    // Named accessors kept as thin wrappers: they read far better at the call
    // sites that check one specific thing (controllers, policies, Blade).
    public function canDownloadDocuments(): bool
    {
        return $this->alumniCan('downloads');
    }

    public function canAccessDocuments(): bool
    {
        return $this->alumniCan('documents');
    }

    public function canAccessJobs(): bool
    {
        return $this->alumniCan('jobs');
    }

    public function canApplyToJobs(): bool
    {
        return $this->alumniCan('applications');
    }

    public function canReceiveNotifications(): bool
    {
        return $this->alumniCan('notifications');
    }

    public function canSeeTicker(): bool
    {
        return $this->alumniCan('ticker');
    }

    public function canAccessProfile(): bool
    {
        return $this->alumniCan('profile');
    }

    public function canSeeAnnouncements(): bool
    {
        return $this->alumniCan('announcements');
    }

    /**
     * Super Admin "HR Control" switches — the HR-side mirror of the alumni
     * capability flags above.
     *
     * Keyed by the capability name used in the Feature Registry and the Super
     * Admin UI. Kept as one map rather than eleven accessors because, unlike
     * the alumni flags, these are only ever consulted generically.
     */
    public const HR_CAPABILITY_COLUMNS = [
        'documents' => 'hr_documents_disabled_at',
        // 'document_categories' removed: replaced by per-category hr_enabled
        // on document_categories. The column remains on the table but is no
        // longer a capability — see FeatureRegistry.
        'jobs' => 'hr_jobs_disabled_at',
        'applications' => 'hr_applications_disabled_at',
        'announcements' => 'hr_announcements_disabled_at',
        'ticker' => 'hr_ticker_disabled_at',
        'alumni' => 'hr_alumni_disabled_at',
        'alumni_master' => 'hr_alumni_master_disabled_at',
        'dashboard' => 'hr_dashboard_disabled_at',
        'settings' => 'hr_settings_disabled_at',
        'notifications' => 'hr_notifications_disabled_at',
    ];

    /**
     * HR switches that start OFF, so Super Admin must grant them deliberately.
     *
     * Every other switch is opt-out: the column is a nullable "disabled_at",
     * NULL means on, and a new HR admin therefore has it. That is right for
     * day-to-day work (documents, jobs, alumni) but wrong for managing the
     * document CATEGORY taxonomy, which is structural and shared by every
     * alumnus — deleting a category is not an HR-routine action, and a fresh
     * HR admin was getting add/edit/delete without Super Admin ever enabling
     * anything.
     *
     * Applied at creation (HrAdminController) and backfilled by migration for
     * admins who already exist. Nothing else needs to know: once the column is
     * set, it is an ordinary switch that Super Admin toggles in HR Control.
     */
    public const HR_DEFAULT_OFF_CAPABILITIES = [
        // Empty since document_categories became a per-category grant on
        // document_categories.hr_enabled (which carries the opt-in default
        // instead). The mechanism stays: any HR switch listed here starts off
        // and must be granted deliberately.
    ];

    /** Columns to stamp as disabled when provisioning a new HR admin. */
    public static function hrDefaultOffColumns(): array
    {
        return array_map(
            fn (string $capability) => self::HR_CAPABILITY_COLUMNS[$capability],
            self::HR_DEFAULT_OFF_CAPABILITIES,
        );
    }

    /** Whether an HR capability is switched on for this admin. */
    public function hrCan(string $capability): bool
    {
        $column = self::HR_CAPABILITY_COLUMNS[$capability] ?? null;

        // Unknown name, or a non-HR user: nothing to restrict. Routes are
        // realm-scoped, so this only ever runs for HR admins in practice.
        if ($column === null || $this->role !== 'hr_admin') {
            return true;
        }

        return $this->{$column} === null;
    }

    /** All HR capability states, for the API payload. */
    public function hrCapabilities(): array
    {
        if ($this->role !== 'hr_admin') {
            return [];
        }

        return collect(self::HR_CAPABILITY_COLUMNS)
            ->map(fn (string $column) => $this->{$column} === null)
            ->all();
    }

    /**
     * A short-lived signed URL that streams this user's avatar, or null if none.
     * Avatars live on the private disk and are never served from a public path.
     */
    public function avatarUrl(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }

        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'avatars.show',
            now()->addDays(7),
            ['user' => $this->id],
            absolute: false, // see Alumni\DocumentController::requestDownload
        );
    }
}
