<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            // End-to-end test support: OTP readback and fixture seeding, which
            // a browser cannot do for itself. Registered only outside
            // production, so on a real deployment these routes do not exist at
            // all rather than existing behind a check. routes/testing.php
            // re-asserts the same guard per route, and deploy/manifest.psd1
            // does not ship the file.
            if (! app()->environment('production')) {
                require __DIR__.'/../routes/testing.php';
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Stamps every request with an id and pushes it, plus the
        // authenticated user, into the log context. First in the stack so a
        // failure anywhere downstream is still attributable.
        $middleware->prepend(\App\Http\Middleware\AssignRequestId::class);

        // Never try to redirect an unauthenticated API caller to a login page.
        //
        // Laravel's default builds route('login') inside Authenticate before
        // any exception rendering happens, so in an API-only app it throws
        // RouteNotFoundException and the caller gets a 500 — the failure the
        // shouldRenderJsonWhen() below cannot reach, because the crash happens
        // upstream of the renderer. Returning null skips the redirect entirely
        // and lets a plain AuthenticationException surface as 401.
        $middleware->redirectGuestsTo(
            fn (\Illuminate\Http\Request $request) => $request->is('api/*') ? null : '/login'
        );

        // Spatie RBAC middleware aliases used by the realm route groups.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            // The single access gate — role permission AND feature switch in
            // one entry, resolved from App\Support\FeatureRegistry.
            'feature' => \App\Http\Middleware\EnsureFeature::class,
            'extend.token' => \App\Http\Middleware\ExtendTokenExpiry::class,
            // Identical to `throttle:` except on a local dev machine, where it
            // steps aside. Used on the auth endpoints only — see the class.
            'throttle.auth' => \App\Http\Middleware\ThrottleUnlessLocal::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Everything under /api is JSON, including its errors.
        //
        // Without this, an unauthenticated request that does not send
        // `Accept: application/json` takes Laravel's browser path: it tries to
        // redirect the guest to route('login'), which does not exist in this
        // API-only app, and the caller gets a 500 RouteNotFoundException
        // instead of a 401. An expired token is exactly that case, so session
        // expiry surfaced as a server error to any client that did not set the
        // header — and the real cause (the token) was invisible in the
        // response.
        $exceptions->shouldRenderJsonWhen(
            fn (\Illuminate\Http\Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        // Framework exception messages must never reach an end user.
        //
        // Laravel's default renderer only substitutes a generic message when
        // APP_DEBUG is false. With debug on — every developer machine, and any
        // deployment where the flag is wrong — it renders the raw exception
        // text instead, which is how users were shown
        //   "No query results for model [App\Models\User]."
        // and
        //   "There is no permission named `service_request.view` for guard `web`."
        // mid-way through activating an account. Both are internal detail: the
        // first names a model and hints at row existence, the second exposes
        // the permission catalog. Neither tells the person what to do next.
        //
        // These map the leak-prone types to a stable, user-facing message
        // REGARDLESS of APP_DEBUG, so behaviour is identical in local, staging
        // and production. The underlying exception is still reported to the
        // log with its full stack trace, so nothing is lost for debugging.
        // Typed on NotFoundHttpException, NOT ModelNotFoundException, and that
        // distinction is the whole bug: Handler::render() calls
        // prepareException() — which converts ModelNotFoundException into
        // NotFoundHttpException, carrying the original as `previous` — BEFORE
        // it consults render callbacks. A callback typed on the Eloquent
        // exception is therefore never reached, which is exactly the gap the
        // raw "No query results for model [App\Models\User]" fell through.
        $exceptions->render(function (NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $fromModel = $e->getPrevious() instanceof ModelNotFoundException;

            if ($fromModel) {
                report($e->getPrevious());
            }

            return response()->json([
                'message' => $fromModel
                    ? 'We could not find the record for that request. Check the details and try again.'
                    : 'The requested endpoint does not exist.',
            ], 404);
        });

        // Spatie throws these when a permission or role name no longer exists —
        // always a server-side configuration drift (a catalog entry removed
        // while something still references it), never anything the caller did.
        $exceptions->render(function (PermissionDoesNotExist|RoleDoesNotExist $e, \Illuminate\Http\Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            report($e);

            return response()->json([
                'message' => 'Something went wrong on our end. Please try again, or contact support if it persists.',
            ], 500);
        });
    })->create();
