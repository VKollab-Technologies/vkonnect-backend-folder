# VKONNECT — Backend (Laravel 12 API)

> **Status: running.** Real authentication across three isolated realms + a live
> Super Admin dashboard endpoint, verified end-to-end against the React frontend.

This is a standard Laravel 12 app; VKONNECT-specific code lives alongside the
framework defaults. See [`../docs/`](../docs/) for the full architecture, schema,
and API contract.

## Toolchain (installed per-user, no admin)

PHP 8.3 and Composer were installed via winget into your user profile. Use the
helper script at the **project root** so you don't have to hunt for paths or
restart your shell:

```powershell
# from c:\Users\geeth\OneDrive\Desktop\VK AP
./backend-cli.ps1 artisan migrate:fresh --seed
./backend-cli.ps1 serve            # http://localhost:8000
./backend-cli.ps1 composer ...     # any composer command
./backend-cli.ps1 php -v
```

## Database architecture

**Primary database: MySQL 8** — all business data (users, alumni, HR, jobs,
applications, documents metadata, announcements, notifications, audit logs,
settings, RBAC) *and* the cache, queue and session tables.

There is no second datastore. Cache, queue and session all use Laravel's
`database` driver, so MySQL is the only service the backend needs.
OTP codes are transient and live in the `cache` table with a 10-minute TTL —
not in a business table, and not in an external store.

- MySQL 8.0 → `127.0.0.1:3306`, database `vkonnect`, app user `vkonnect_app`
  (a least-privilege account scoped to that one schema — never `root`)

`.env` (values come from your own environment; never commit real ones):
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vkonnect
DB_USERNAME=<app db user>
DB_PASSWORD=<app db password>

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

`QUEUE_CONNECTION=sync` is used during local testing so mail sends inline and
no worker is needed. Any other value requires a supervised
`php artisan queue:work`, or queued mail (OTP included) never leaves.

Start everything: `./backend-cli.ps1 setup` → `./backend-cli.ps1 serve`.
Actual files (documents, resumes) live on the private disk `storage/app/private`.

## Implemented this phase

- **UUID** `users` table, soft deletes, account-lock fields; `alumni_profiles`,
  `hr_admins` (UUID FKs); Sanctum + Spatie tables adapted to UUID morphs.
- **Spatie RBAC** — roles `super_admin` / `hr_admin` / `alumni` + 10 permissions,
  seeded; `Gate::before` gives Super Admin every ability.
- **Three isolated auth realms** (Sanctum tokens, realm-scoped abilities,
  cross-realm login rejected): `POST /api/v1/{super-admin|hr|alumni}/auth/login`,
  `GET .../auth/me`, `POST .../auth/logout`, `POST /api/v1/alumni/auth/register`.
- **Login throttling** (`throttle:6,1`), status checks (disabled/pending), enterprise
  password policy on register (mirrors the frontend Zod schema).
- **Super Admin dashboard** — `GET /api/v1/super-admin/dashboard` returns the exact
  payload the frontend consumes, computed from real DB state.
- Thin controllers → Form Requests → `AuthService` → API Resources; CORS configured
  for the Vite dev origin.

## Seeded demo credentials

| Realm        | Employee ID | Login route          | Email                    | Password     |
|--------------|-------------|----------------------|--------------------------|--------------|
| Super Admin  | `SA1001`    | `/super-admin/login` | `kgsmanikanta1010@gmail.com` | `REDACTED-CREDENTIAL-SCRUBBED-FROM-HISTORY`  |
| HR Admin     | `HR1001`    | `/hr/login`          | `geethasaimanikanta.k@gmail.com`    | `REDACTED-CREDENTIAL-SCRUBBED-FROM-HISTORY`     |
| Alumni       | `ALM1001`   | `/alumni/login`      | `kgsmanikanta1010@gmail.com`     | `Alumni@123` |

These are **real `users` rows** created by `database/seeders/DatabaseSeeder.php`
(hashed passwords, `employee_id`, role + permissions). Login validates against the
database via Sanctum — there is no frontend/mock authentication.

## Run the full stack

```powershell
# Terminal 1 — API
./backend-cli.ps1 serve                       # http://localhost:8000

# Terminal 2 — SPA (frontend/.env has VITE_USE_MOCKS=false)
cd frontend; npm run dev                       # http://localhost:5173
```
Sign in at `/super-admin/login` with the credentials above — the dashboard now
shows **live data from Laravel**, not mocks.

## Not yet built (next phases)
- Remaining entities (documents w/ signed URLs, jobs, applications, announcements,
  audit/activity logs) — designed in `../docs/02-database-schema.md`.
- HR & Alumni dashboard endpoints + management modules.
- OTP email verification & password-reset brokers (scaffolded in the contract).
