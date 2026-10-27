<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This backend is an API only — the interface is a separate React app, and
| every real endpoint lives under /api/v1 (see routes/api.php).
|
| The root path used to render Laravel's stock welcome view, which pulled in
| a Vite bundle that was never built here. Anyone hitting the backend host
| directly got a manifest error rather than an answer. It returns a plain
| identifying payload instead: enough for a human, a health check, or a
| load balancer to see the service is up and which API it speaks.
|
*/


Route::get('/{any}', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');
