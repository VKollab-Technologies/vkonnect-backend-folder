<?php

namespace App\Services;

use App\Models\AlumniMaster;
use App\Models\AlumniProfile;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Authentication business logic shared by all three realm auth controllers.
 * Enforces realm isolation, account status, and a simple lockout policy, and
 * issues realm-scoped Sanctum tokens.
 */
class AuthService
{
    /**
     * Per-account lockout thresholds, by realm.
     *
     * Admins get a looser limit and a longer lock: they sign in from shared
     * office IPs and manage several accounts, so the alumni threshold produced
     * false lockouts of the people who would have to unlock them. The longer
     * lock offsets the higher attempt count so the guessing rate an attacker
     * gets is not meaningfully better — 15 attempts per 30 min versus 5 per 15
     * min is 30/hr against 20/hr.
     *
     * Both realms keep the protection; only the numbers differ.
     */
    private const MAX_ATTEMPTS = 5;

    private const LOCK_MINUTES = 15;

    private const ADMIN_MAX_ATTEMPTS = 15;

    private const ADMIN_LOCK_MINUTES = 30;

    /** @return array{0: int, 1: int} [maxAttempts, lockMinutes] for this user's realm. */
    public static function lockoutPolicy(User $user): array
    {
        return in_array($user->role, ['hr_admin', 'super_admin'], true)
            ? [self::ADMIN_MAX_ATTEMPTS, self::ADMIN_LOCK_MINUTES]
            : [self::MAX_ATTEMPTS, self::LOCK_MINUTES];
    }

    /** Per-IP brute-force detection, independent of the per-account lockout above. */
    private const IP_FAIL_THRESHOLD = 10;
    private const IP_FAIL_WINDOW_MINUTES = 5;
    private const IP_ALERT_COOLDOWN_MINUTES = 60;

    /**
     * Whether brute-force protections apply. False ONLY on a local developer
     * machine (APP_ENV=local).
     *
     * Repeated deliberate bad logins are normal during manual testing, and
     * locking the one seeded account for 15-30 minutes — or paging every Super
     * Admin about a brute-force attempt — stops the testing rather than
     * protecting anything.
     *
     * Deliberately keyed to `local` alone, not `! production`: `testing` must
     * stay protected so the suite keeps exercising lockout, throttling and the
     * IP alert for real. Staging behaves exactly like production.
     *
     * This is the single switch behind all three protections (per-account
     * lockout, per-IP alert, and — via ThrottleUnlessLocal — the route
     * throttles), so there is one place to check when reasoning about what is
     * active in a given environment.
     */
    public static function protectionsApply(): bool
    {
        return ! app()->environment('local');
    }

    public function __construct(private readonly NotificationService $notifications) {}

    /**
     * Validate credentials + realm + account status for a realm WITHOUT issuing
     * a token. This is step 1 of the two-step (password → OTP) login. Returns
     * the authenticated user; throws on any failure.
     */
    public function verifyCredentials(string $realm, string $vkollabId, string $password): User
    {
        $user = User::whereVkollabId($vkollabId)->first();

        // Generic failure message — never reveal which factor failed. Covers
        // exactly the three brute-force-relevant cases (unknown id, wrong
        // password, realm mismatch) — locked/disabled/pending below are
        // valid-credential-but-blocked-account cases, not guessing attempts.
        $fail = function () use ($vkollabId, $realm) {
            AuditLogger::logFailure('auth.login', 'auth', ['vkollabId' => $vkollabId, 'realm' => $realm]);
            $this->registerFailedIpAttempt();
            throw ValidationException::withMessages([
                'vkollabId' => ['These credentials do not match our records.'],
            ]);
        };

        if (! $user) {
            $fail();
        }

        if (! Hash::check($password, $user->password)) {
            $this->registerFailedAttempt($user);
            $fail();
        }

        if ($user->isLocked()) {
            AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'locked'], 'failure');
            throw ValidationException::withMessages([
                'vkollabId' => ['Account temporarily locked. Try again later.'],
            ]);
        }

        // Realm isolation: a session in one realm cannot cross into another.
        if ($user->role !== $realm) {
            $fail();
        }

        if ($user->status === 'disabled') {
            AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'disabled'], 'failure');
            throw ValidationException::withMessages([
                'vkollabId' => ['This account has been disabled. Contact your administrator.'],
            ]);
        }

        if ($user->status === 'pending') {
            AuditLogger::logFor($user, 'auth.login', 'auth', ['reason' => 'pending'], 'failure');
            throw ValidationException::withMessages([
                'vkollabId' => ['Your account is pending approval.'],
            ]);
        }

        // The counter tracks failed PASSWORD attempts, so a correct password
        // clears it here rather than in issueToken(). Now that every realm has
        // a second step, waiting for the token would leave someone who signed
        // in correctly but abandoned the OTP still carrying strikes — and one
        // typo later would lock an account whose owner had just proved they
        // know the password.
        // locked_until goes with it: by this point isLocked() has already
        // passed, so any value still there is an expired lock, and leaving it
        // behind makes the column read as "locked" to anything that inspects
        // it directly rather than calling isLocked().
        if ($user->failed_login_attempts > 0 || $user->locked_until !== null) {
            $user->forceFill(['failed_login_attempts' => 0, 'locked_until' => null])->save();
        }

        return $user;
    }

    /**
     * Per-account lockout, complementing the route-level per-IP throttle
     * (ThrottleRequests) which doesn't distinguish between attackers spraying
     * many accounts and one attacker hammering a single account. Distinct from
     * the manual HR/SuperAdmin "lock account" action (`alumni.locked` etc.,
     * which sets locked_until 100 years out as a deliberate admin decision) —
     * this one is audited as `account.auto_locked` so the two never get
     * confused in the trail, even though the user sees the same generic
     * "temporarily locked" message either way.
     */
    private function registerFailedAttempt(User $user): void
    {
        // Local only: no counter, no lock. The audit record of the failure is
        // still written by the caller, so the attempt is not invisible.
        if (! self::protectionsApply()) {
            return;
        }

        [$maxAttempts, $lockMinutes] = self::lockoutPolicy($user);

        $attempts = $user->failed_login_attempts + 1;
        $update = ['failed_login_attempts' => $attempts];

        if ($attempts >= $maxAttempts) {
            $update['locked_until'] = now()->addMinutes($lockMinutes);
        }

        $user->forceFill($update)->save();

        if ($attempts >= $maxAttempts) {
            AuditLogger::logFor($user, 'account.auto_locked', 'auth', [
                'attempts' => $attempts,
                'lockedForMinutes' => $lockMinutes,
            ], 'failure');
        }
    }

    /**
     * Per-IP brute-force detection — spraying different VKollab IDs or
     * hammering one account from a single IP both count against the same
     * counter, unlike the per-account lockout above. A fixed 5-minute window:
     * the counter is created with a TTL on the first failure and merely
     * incremented (never re-extended) after that, so it resets on its own if
     * an attacker paces attempts slower than the window rather than growing
     * unbounded. At the threshold, notifies Super Admin exactly once per
     * cooldown window via a separate "already alerted" flag — without it,
     * every attempt past #10 would re-fire the notification.
     */
    private function registerFailedIpAttempt(): void
    {
        // Local only: manual testing trips the 10-in-5-minutes threshold in
        // seconds and would notify every Super Admin repeatedly.
        if (! self::protectionsApply()) {
            return;
        }

        $ip = request()->ip();
        $key = "login_fail_ip:{$ip}";

        if (Cache::has($key)) {
            $count = Cache::increment($key);
        } else {
            $count = 1;
            Cache::put($key, $count, now()->addMinutes(self::IP_FAIL_WINDOW_MINUTES));
        }

        if ($count < self::IP_FAIL_THRESHOLD) {
            return;
        }

        $alertKey = "login_fail_ip_alerted:{$ip}";
        if (Cache::has($alertKey)) {
            return;
        }
        Cache::put($alertKey, true, now()->addMinutes(self::IP_ALERT_COOLDOWN_MINUTES));

        $this->notifications->notifyRoles(
            ['super_admin'],
            'system',
            'Possible brute-force login activity',
            "{$count} failed login attempts from IP {$ip} within ".self::IP_FAIL_WINDOW_MINUTES.' minutes.',
            '/super-admin/audit-logs',
        );

        AuditLogger::log('security.brute_force_alert', 'auth', null, [
            'ip' => $ip,
            'attempts' => $count,
        ]);
    }

    /** How long a session survives with no activity — see ExtendTokenExpiry, which pushes this out on every authenticated request. */
    public const SESSION_HOURS = 1;

    /**
     * Step 2: issue a realm-scoped Sanctum token for an already-verified user.
     * The token's real expiry is a sliding 24h window (ExtendTokenExpiry
     * middleware keeps extending it on activity); the value returned here is
     * just today's snapshot of that same deadline, for the frontend to display.
     *
     * @return array{user: User, token: string, expiresAt: string}
     */
    public function issueToken(User $user, string $realm): array
    {
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ])->save();

        $expiresAt = now()->addHours(self::SESSION_HOURS);
        $token = $user->createToken("{$realm}-token", ["{$realm}:*"], $expiresAt)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'expiresAt' => $expiresAt->toIso8601String(),
        ];
    }

    /**
     * Activate an EXISTING alumni record (from the master roster) into a real
     * login account. Called only after identity + OTP have been verified. Does
     * not create roster rows — it claims the pre-existing one and sets the
     * password. The alumnus is pre-approved (HR listed them), so status=active.
     */
    public function activateFromMaster(AlumniMaster $master, string $password): User
    {
        $this->assertAlumniIdentityAvailable($master);

        return DB::transaction(function () use ($master, $password) {
            $user = User::create([
                'name' => $master->name,
                'email' => $master->email,
                'vkollab_id' => $master->vkollab_id,
                'password' => $password, // hashed via cast
                'role' => 'alumni',
                'status' => 'active', // pre-approved via the HR-managed master roster
                'email_verified_at' => now(),
            ]);

            $user->assignRole('alumni');
            $user->syncPermissions(app(RolePermissionService::class)->defaults('alumni'));

            AlumniProfile::create([
                'user_id' => $user->id,
                'vkollab_id' => $master->vkollab_id,
                'pan' => $master->pan,
                'designation' => $master->designation,
                'department' => $master->department,
                'current_organization' => $master->organization,
                'phone' => $master->phone,
                'location' => $master->location,
                'joined_on' => $master->joined_on,
                'exited_on' => $master->exited_on,
                'last_working_location' => $master->last_working_location,
                'band' => $master->band,
                'supervisor_name' => $master->supervisor_name,
                'supervisor_email' => $master->supervisor_email,
                'uan_number' => $master->uan_number,
                'pf_number' => $master->pf_number,
                'profile_completion' => 30,
            ]);

            $master->forceFill(['claimed_by' => $user->id, 'claimed_at' => now()])->save();

            // Hand over any documents HR filed against the roster record
            // before this person registered, so their vault is already
            // populated on first sign-in. The stored file paths are keyed by
            // the roster id and stay where they are — only ownership moves.
            Document::withTrashed()
                ->where('alumni_master_id', $master->id)
                ->update(['user_id' => $user->id, 'alumni_master_id' => null]);

            return $user;
        });
    }

    /**
     * Fail with a readable message instead of a raw DB integrity error.
     *
     * The roster row being claimed can be perfectly valid while the resulting
     * account still collides: another LIVE alumni account may already hold the
     * same email or EMP ID (e.g. the roster was re-imported under a new EMP ID
     * for someone who already registered). `$master->isClaimed()` only knows
     * about its own row, so it cannot catch that. Soft-deleted accounts are
     * ignored here on purpose — a deleted account must never permanently
     * reserve an email or EMP ID (see the
     * 2026_07_31_000001_exclude_soft_deleted_users_from_unique_constraints
     * migration).
     */
    private function assertAlumniIdentityAvailable(AlumniMaster $master): void
    {
        if ($master->email && User::where('role', 'alumni')->where('email', $master->email)->exists()) {
            throw ValidationException::withMessages([
                'token' => ['An active account already uses this email address. Please sign in instead, or contact HR.'],
            ]);
        }

        if ($master->vkollab_id && User::where('vkollab_id', $master->vkollab_id)->exists()) {
            throw ValidationException::withMessages([
                'token' => ['An active account already exists for this EMP ID. Please sign in instead, or contact HR.'],
            ]);
        }
    }
}
