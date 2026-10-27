<?php

namespace App\Console\Commands;

use App\Models\RolePermissionDefault;
use App\Models\User;
use App\Support\Permissions as Catalog;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Reconciles the permissions table with App\Support\Permissions (the catalog
 * that is the single source of truth) and re-applies role defaults.
 *
 * Adding an entry to the catalog only changes PHP — the Spatie tables are
 * unaware of it until rows exist, so a brand-new permission silently denies
 * for everyone, including behind a Gate::before short-circuit that makes
 * Super Admin look fine while HR and alumni are locked out. Running this after
 * a deploy that touched the catalog is what closes that gap.
 *
 * Users with an individual permission profile (permissions_customized_at set)
 * are deliberately skipped: a Super Admin has hand-tuned them, and role
 * defaults must never silently overwrite that.
 */
class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync
        {--dry-run : Show what would change without writing}
        {--adopt-new : Also grant newly-created permissions to users with a customized profile}';

    protected $description = 'Create missing permissions from the catalog and re-apply role defaults';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $existing = Permission::pluck('name')->all();
        $missing = array_values(array_diff(Catalog::all(), $existing));

        if ($missing) {
            $this->line('Missing permissions: '.implode(', ', $missing));

            if (! $dryRun) {
                foreach ($missing as $name) {
                    Permission::create(['name' => $name, 'guard_name' => 'web']);
                }
            }
        } else {
            $this->line('No missing permissions.');
        }

        // Retired permissions leave three kinds of debris, and this command
        // originally cleaned none of them: the `permissions` rows themselves,
        // the user grants (handled by the syncs below), and the stored role
        // templates in `role_permission_defaults`. The last one is the
        // dangerous one — it is read on account activation and fed straight to
        // syncPermissions(), so a stale name there throws PermissionDoesNotExist
        // at a real user rather than merely lingering.
        $staleDefaults = RolePermissionDefault::whereNotIn('permission', Catalog::all())->get();

        if ($staleDefaults->isNotEmpty()) {
            $this->line('Stale role defaults: '.$staleDefaults
                ->map(fn ($d) => "{$d->role}/{$d->permission}")->implode(', '));

            if (! $dryRun) {
                RolePermissionDefault::whereNotIn('permission', Catalog::all())->delete();
            }
        } else {
            $this->line('No stale role defaults.');
        }

        // Orphan permission rows: in the table but no longer in the catalog.
        // Deleting cascades to model_has_permissions / role_has_permissions.
        $orphans = Permission::whereNotIn('name', Catalog::all())->pluck('name')->all();

        if ($orphans) {
            $this->line('Orphan permissions: '.implode(', ', $orphans));

            if (! $dryRun) {
                Permission::whereIn('name', $orphans)->delete();
            }
        } else {
            $this->line('No orphan permissions.');
        }

        if ($dryRun) {
            $this->info('Dry run — nothing written.');

            return self::SUCCESS;
        }

        // Spatie caches the whole permission map; without this the syncs below
        // resolve against the pre-create snapshot and quietly drop the new names.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $rows = [];
        foreach (['hr_admin', 'alumni'] as $role) {
            $users = User::where('role', $role)->whereNull('permissions_customized_at')->get();

            foreach ($users as $user) {
                $user->syncPermissions(Catalog::defaultsFor($role));
            }

            $customized = User::where('role', $role)->whereNotNull('permissions_customized_at')->get();
            $adopted = 0;

            // A customized profile means a Super Admin deliberately chose which
            // permissions this person holds — role defaults must not overwrite
            // that. Permissions created *after* that choice was made are the
            // exception: they did not exist at the time, so the admin never
            // decided anything about them and leaving them off is a gap rather
            // than a decision. Keying on created_at (not "missing in this run")
            // makes this idempotent and safe to run on any schedule: a
            // deliberately revoked older permission is never silently restored.
            if ($this->option('adopt-new')) {
                $defaults = Catalog::defaultsFor($role);

                foreach ($customized as $user) {
                    $adoptable = Permission::whereIn('name', $defaults)
                        ->where('created_at', '>', $user->permissions_customized_at)
                        ->pluck('name')
                        ->all();

                    if ($adoptable) {
                        $user->givePermissionTo($adoptable);
                        $adopted++;
                    }
                }
            }

            $rows[] = [$role, $users->count(), $customized->count(), $adopted];
        }

        $this->table(['Role', 'Resynced', 'Customized', 'Adopted new'], $rows);
        $this->info('Permissions synced.');

        if ($missing && ! $this->option('adopt-new')) {
            $this->comment('Users with a customized profile did not receive the new permissions. Re-run with --adopt-new to grant them.');
        }

        return self::SUCCESS;
    }
}
