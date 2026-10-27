<?php

namespace App\Providers;

use App\Mail\Transport\MicrosoftGraphTransport;
use App\Models\User;
use App\Services\Graph\GraphTokenService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Super Admin implicitly passes every authorization check.
        Gate::before(function (User $user, string $ability) {
            return $user->role === 'super_admin' ? true : null;
        });

        // Registers MAIL_MAILER=graph as a Symfony Mailer transport that sends
        // through Microsoft Graph instead of SMTP. See MicrosoftGraphTransport.
        Mail::extend('graph', fn () => new MicrosoftGraphTransport(
            $this->app->make(GraphTokenService::class)
        ));
    }
}
