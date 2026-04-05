<?php

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;

uses(RefreshDatabase::class);

it('triggers duplicate oauth_access_tokens queries for each Auth::check() call with client_credentials token', function () {
    $client = Client::factory()->create([
        'grant_types' => ['client_credentials'],
        'redirect_uris' => [],
        'provider' => null,
        'secret' => 'test-secret',
    ]);

    Country::factory()->count(15)->create();

    // Issue a real client_credentials token through the OAuth endpoint
    $tokenResponse = $this->postJson('/oauth/token', [
        'grant_type' => 'client_credentials',
        'client_id' => $client->id,
        'client_secret' => 'test-secret',
        'scope' => '',
    ]);

    $tokenResponse->assertSuccessful();
    $accessToken = $tokenResponse->json('access_token');

    // Simulate what auth:api middleware does — set the api guard as default.
    // This way Auth::check() in CountryResource hits the Passport TokenGuard.
    Auth::shouldUse('api');

    // Now make the API request and track queries
    $queryLog = [];

    DB::listen(function ($query) use (&$queryLog) {
        if (str_contains($query->sql, 'oauth_access_tokens')) {
            $queryLog[] = $query->sql;
        }
    });

    $response = $this->withToken($accessToken)->getJson('/v1/countries');

    $response->assertSuccessful();

    $duplicateCount = count($queryLog);

    dump("oauth_access_tokens queries executed: {$duplicateCount} (expected ~15+ due to bug, should be ≤2 after fix)");
    dd($queryLog);

    // The bug: TokenGuard::user() uses `is_null($this->user)` which cannot
    // distinguish "never resolved" from "resolved to null" (client_credentials
    // has no user). Each Auth::check() in CountryResource::toArray() re-enters
    // authenticateViaBearerToken() → getPsrRequestViaBearerToken() →
    // BearerTokenValidator → isAccessTokenRevoked(), causing N duplicate
    // oauth_access_tokens queries for a collection of N items.
    //
    // Without fix: 15+ queries (middleware + Auth::check() × 15 resources)
    // With fix: should be ≤2
    expect($duplicateCount)
        ->toBeGreaterThan(2, "Bug NOT reproduced: expected duplicate queries but got {$duplicateCount}");
});
