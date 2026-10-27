<?php

/*
|--------------------------------------------------------------------------
| End-to-End Test Support
|--------------------------------------------------------------------------
|
| Endpoints the Playwright suite needs and a browser cannot otherwise reach:
| the OTP code (which only ever exists in the cache and an inbox), plus
| fixture seeding and teardown.
|
| These are dangerous by definition — /otp hands out a live second factor and
| /seed mints activated accounts. Three independent guards keep them off a
| real deployment:
|
|   1. The file is only registered when the app is NOT in production
|      (bootstrap/app.php), so in production the routes do not exist.
|   2. Every route re-checks the environment, so registering the file by
|      mistake still fails closed.
|   3. deploy/manifest.psd1 ships routes/api.php, web.php and console.php by
|      name — this file is not on the list, so it cannot reach a server.
|
| Everything seeded carries a per-run tag so teardown deletes exactly what a
| run created and nothing else. Tests therefore share no state and can run in
| any order, repeatedly.
|
*/

use App\Models\Alert;
use App\Models\AlumniMaster;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Support\Permissions as PermissionCatalog;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

Route::prefix('api/testing')->middleware('api')->group(function () {

    /**
     * The OTP for an email + purpose.
     *
     * Read out of the delivered mail, not the cache: OtpService stores only a
     * bcrypt hash of the code, which is correct and must stay that way. Under
     * MAIL_MAILER=log the rendered message lands in the application log, which
     * is the closest thing this project has to a Mailpit inbox.
     *
     * Reading the mail rather than short-circuiting OtpService also means the
     * test exercises the real issue-and-send path. A test-only branch inside
     * OtpService would have proved less and cost production code.
     */
    Route::get('otp', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'purpose' => ['required', 'in:login,register,reset'],
        ]);

        // Confirm a live code exists for THIS purpose before trusting the mail,
        // so a stale message for a different purpose cannot be picked up.
        $live = Cache::get('otp:'.$data['purpose'].':'.sha1(mb_strtolower(trim($data['email']))));
        abort_if($live === null, 404, 'No OTP outstanding for that address and purpose.');

        $subject = match ($data['purpose']) {
            'register' => 'VKONNECT Account Verification OTP',
            'reset' => 'VKONNECT Password Reset OTP',
            'login' => 'Your VKONNECT Login Verification Code',
        };

        $logFile = storage_path('logs/laravel.log');
        abort_unless(is_readable($logFile), 404, 'No mail log to read. Run the API with MAIL_MAILER=log.');

        // Tail the log rather than loading it whole; it grows without bound.
        $handle = fopen($logFile, 'r');
        fseek($handle, max(0, filesize($logFile) - 512_000));
        $tail = (string) fread($handle, 512_000);
        fclose($handle);

        $code = null;

        // Split on the log's own line ending rather than PHP_EOL: on Windows
        // PHP_EOL is CRLF, which never matched, leaving the tail as one blob so
        // the first code anywhere in it won — a stale one.
        foreach (array_reverse(preg_split('/\r?\n/', $tail)) as $line) {
            if (! str_contains($line, $data['email']) || ! str_contains($line, $subject)) {
                continue;
            }
            $message = json_decode($line, true)['message'] ?? $line;
            if (preg_match('/>\s*(\d{6})\s*</', $message, $m)) {
                $code = $m[1];
                break;
            }
        }

        abort_if($code === null, 404, 'A code is live but no matching email was found in the log.');

        return response()->json(['code' => $code]);
    });

    /** An UNREGISTERED roster row — the starting point of the activation flow. */
    Route::post('seed/roster', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $tag = $request->validate(['tag' => ['required', 'string', 'max:40']])['tag'];

        $record = AlumniMaster::create([
            'vkollab_id' => $tag,
            'name' => 'E2E Roster '.$tag,
            'email' => Str::lower($tag).'@e2e.test',
            'status' => 'active',
            'joined_on' => '2020-01-01',
            'exited_on' => '2024-01-01',
        ]);

        return response()->json([
            'vkollabId' => $record->vkollab_id,
            'email' => $record->email,
        ], 201);
    });

    /** An ACTIVATED account in any realm, with a known password. */
    Route::post('seed/user', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $data = $request->validate([
            'role' => ['required', 'in:alumni,hr_admin,super_admin'],
            'tag' => ['required', 'string', 'max:40'],
            'password' => ['required', 'string'],
            'permissions' => ['array'],
        ]);

        Role::findOrCreate($data['role']);

        $user = User::create([
            'name' => 'E2E '.$data['tag'],
            'email' => Str::lower($data['tag']).'@e2e.test',
            'vkollab_id' => $data['tag'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $user->assignRole($data['role']);

        // Default to the realm's standard permission set, exactly as
        // DatabaseSeeder does. A role alone grants nothing here — permissions
        // are held per user — so a role-only account can sign in and then find
        // every feature empty, which looks like a bug in the feature rather
        // than an incomplete fixture.
        $permissions = ($data['permissions'] ?? []) ?: PermissionCatalog::defaultsFor($data['role']);

        if ($permissions) {
            foreach ($permissions as $permission) {
                Permission::findOrCreate($permission);
            }
            $user->givePermissionTo($permissions);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return response()->json([
            'id' => $user->id,
            'vkollabId' => $user->vkollab_id,
            'email' => $user->email,
        ], 201);
    });

    /** A job posting, open or closed, for the application flow. */
    Route::post('seed/job', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $data = $request->validate([
            'tag' => ['required', 'string', 'max:40'],
            'closed' => ['boolean'],
        ]);

        $job = JobPosting::create([
            'title' => 'E2E Engineer '.$data['tag'],
            'organization' => 'VKollab Technologies',
            'description' => 'Seeded by the end-to-end suite. Long enough to satisfy the minimum length rule.',
            'location' => 'Remote',
            'status' => ($data['closed'] ?? false) ? 'closed' : 'open',
        ]);

        return response()->json(['id' => $job->id, 'title' => $job->title], 201);
    });

    /**
     * A ticker alert at a chosen severity.
     *
     * Exists for the critical case specifically: a critical alert is supposed
     * to stop the marquee and hold, and there is no way to assert that without
     * a real one in the feed.
     */
    Route::post('seed/alert', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $data = $request->validate([
            'tag' => ['required', 'string', 'max:40'],
            'level' => ['required', 'in:info,success,warning,critical'],
            'audience' => ['nullable', 'in:all,alumni,hr'],
        ]);

        $alert = Alert::create([
            'message' => 'E2E '.$data['level'].' alert '.$data['tag'],
            'level' => $data['level'],
            'audience' => $data['audience'] ?? 'all',
            'is_active' => true,
        ]);

        return response()->json(['id' => $alert->id, 'message' => $alert->message], 201);
    });

    /**
     * A real document file owned by a specific alumnus.
     *
     * Writes actual bytes through the same disk the app uses, so the download
     * path under test is the real one — signed URL, stream, headers and all.
     */
    Route::post('seed/document', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $data = $request->validate([
            'userId' => ['required', 'string'],
            'tag' => ['required', 'string', 'max:40'],
        ]);

        // Prefer Personal Documents: the alumni vault groups by category, and
        // seeding into an arbitrary one hides the fixture behind a collapsed
        // accordion the test would have to guess at.
        $category = DocumentCategory::query()->where('name', 'Personal Documents')->first()
            ?? DocumentCategory::query()->orderBy('name')->first();
        abort_if($category === null, 500, 'No document categories exist; run the seeder.');

        $bytes = "%PDF-1.4
% E2E fixture for ".$data['tag']."
%%EOF
";
        $path = 'documents/'.$data['userId'].'/e2e-'.$data['tag'].'.pdf';
        \App\Support\Disks::documents()->put($path, $bytes);

        $document = Document::create([
            'user_id' => $data['userId'],
            'category_id' => $category->id,
            'title' => 'E2E Document '.$data['tag'],
            'disk_path' => $path,
            'original_name' => 'e2e-'.$data['tag'].'.pdf',
            'mime_type' => 'application/pdf',
            'size_kb' => max(1, (int) ceil(strlen($bytes) / 1024)),
        ]);

        return response()->json([
            'id' => $document->id,
            'title' => $document->title,
            'bytes' => strlen($bytes),
        ], 201);
    });

    /** Categories present, so upload targets are deterministic rather than guessed. */
    Route::get('document-categories', function () {
        abort_if(app()->environment('production'), 404);

        return response()->json(
            DocumentCategory::query()->orderBy('name')->get(['id', 'name', 'slug', 'hr_enabled'])
        );
    });

    /**
     * Delete everything a run created, matched on its tag.
     *
     * Force-deletes deliberately: soft-deleted rows still occupy the unique
     * slots for EMP ID and email, so a re-run under the same tag would collide
     * with its own leftovers.
     */
    Route::delete('cleanup', function (Request $request) {
        abort_if(app()->environment('production'), 404);

        $tag = $request->validate(['tag' => ['required', 'string', 'max:40']])['tag'];
        $like = $tag.'%';

        $userIds = User::withTrashed()->where('vkollab_id', 'like', $like)->pluck('id');

        Document::withTrashed()->whereIn('user_id', $userIds)->forceDelete();
        JobPosting::withTrashed()->where('title', 'like', '%'.$tag.'%')->forceDelete();
        Alert::where('message', 'like', '%'.$tag.'%')->forceDelete();
        AlumniMaster::withTrashed()->where('vkollab_id', 'like', $like)->forceDelete();

        /*
         * Spatie's role/permission tables and Sanctum's tokens are
         * polymorphic (model_id + model_type), so MySQL cannot hold a foreign
         * key against them and nothing cascades when a user is force-deleted.
         * Without this the rows outlive their owner forever: a single day of
         * E2E runs left 25,533 of them behind, against 12 live users.
         *
         * Deleted before the user, so a failure here cannot orphan the rows it
         * was meant to remove.
         */
        if ($userIds->isNotEmpty()) {
            DB::table('model_has_permissions')
                ->where('model_type', User::class)->whereIn('model_id', $userIds)->delete();
            DB::table('model_has_roles')
                ->where('model_type', User::class)->whereIn('model_id', $userIds)->delete();
            DB::table('personal_access_tokens')
                ->where('tokenable_type', User::class)->whereIn('tokenable_id', $userIds)->delete();
        }

        User::withTrashed()->whereIn('id', $userIds)->forceDelete();

        foreach (['login', 'register', 'reset'] as $purpose) {
            Cache::forget('otp:'.$purpose.':'.sha1(Str::lower($tag).'@e2e.test'));
        }

        return response()->json(['deleted' => true, 'tag' => $tag]);
    });
});
