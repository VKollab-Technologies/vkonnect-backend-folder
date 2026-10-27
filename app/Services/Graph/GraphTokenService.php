<?php

namespace App\Services\Graph;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Acquires and caches an Azure AD app-only (client credentials) access token
 * for Microsoft Graph. The token is cached via the configured cache store
 * (the `database` store) under
 * its own expiry so it survives across queue workers/requests instead of being
 * fetched per email — Graph/Entra rate-limits token endpoint calls.
 */
class GraphTokenService
{
    private const CACHE_KEY = 'graph:access_token';

    /** Refresh this many seconds before the token's real expiry, to avoid
     *  handing out a token that dies mid-request. */
    private const EXPIRY_BUFFER_SECONDS = 60;

    private const GRAPH_SCOPE = 'https://graph.microsoft.com/.default';

    /** Get a valid access token, from cache or freshly acquired. */
    public function getToken(): string
    {
        $token = Cache::get(self::CACHE_KEY);

        if (is_string($token) && $token !== '') {
            return $token;
        }

        return $this->acquireToken();
    }

    /** Discard the cached token and acquire a fresh one (used after a 401). */
    public function refreshToken(): string
    {
        Cache::forget(self::CACHE_KEY);

        return $this->acquireToken();
    }

    private function acquireToken(): string
    {
        $tenantId = config('services.microsoft_graph.tenant_id');
        $clientId = config('services.microsoft_graph.client_id');
        $clientSecret = config('services.microsoft_graph.client_secret');

        if (! $tenantId || ! $clientId || ! $clientSecret) {
            throw new GraphAuthException('Microsoft Graph is not configured (missing tenant/client id/secret).');
        }

        $response = Http::asForm()
            ->timeout(10)
            ->retry(2, 500, throw: false)
            ->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => self::GRAPH_SCOPE,
            ]);

        if ($response->failed()) {
            // Never log the client secret; Graph's error body is safe (no secret echoed back).
            Log::error('Microsoft Graph token acquisition failed', [
                'status' => $response->status(),
                'error' => $response->json('error'),
                'error_description' => $response->json('error_description'),
            ]);

            throw new GraphAuthException('Failed to acquire Microsoft Graph access token: '.$response->json('error_description', $response->reason()));
        }

        $accessToken = $response->json('access_token');
        $expiresIn = (int) $response->json('expires_in', 3600);

        if (! $accessToken) {
            throw new GraphAuthException('Microsoft Graph token response did not include an access_token.');
        }

        $ttl = max(60, $expiresIn - self::EXPIRY_BUFFER_SECONDS);
        Cache::put(self::CACHE_KEY, $accessToken, $ttl);

        return $accessToken;
    }
}
