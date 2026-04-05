# Passport `TokenGuard::user()` Duplicate Query Bug Reproduction

Minimal Laravel project demonstrating a bug in [laravel/passport](https://github.com/laravel/passport) where `TokenGuard::user()` re-enters the full OAuth2 validation pipeline on every call for `client_credentials` tokens, causing N duplicate `oauth_access_tokens` queries per request.

## The Bug

`TokenGuard::user()` uses `is_null($this->user)` to cache the resolved user. For `client_credentials` tokens, `authenticateViaBearerToken()` legitimately returns `null` (no user). The guard cannot distinguish "never resolved" from "resolved to null", so every subsequent `Auth::check()` or `Auth::user()` call re-triggers the full token validation.

```php
// TokenGuard::user()
if (! is_null($this->user)) {  // ← always true when user is null
    return $this->user;
}
```

## Reproduction

```bash
git clone <this-repo>
cd <this-repo>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan test --filter=ClientCredentialsDuplicateQueryTest
```

### Expected

≤2 `oauth_access_tokens` queries regardless of collection size.

### Actual

15 identical `oauth_access_tokens` queries for a collection of 15 items:

```sql
select exists(
    select * from "oauth_access_tokens"
    where "oauth_access_tokens"."id" = ? and "revoked" = 0
) as "exists"
```

## How It Works

1. A `client_credentials` OAuth client is created (no user provider)
2. A real token is issued via `/oauth/token`
3. `CountryResource::toArray()` calls `Auth::check()` to conditionally show admin-only fields
4. Each `Auth::check()` re-enters `TokenGuard::user()` → `authenticateViaBearerToken()` → `getPsrRequestViaBearerToken()` → `BearerTokenValidator` → `isAccessTokenRevoked()` — one duplicate query per resource item

## Suggested Fix

Use a sentinel boolean to track whether user resolution has been attempted:

```php
protected bool $userResolved = false;

public function user(): ?Authenticatable
{
    if ($this->userResolved) {
        return $this->user;
    }

    $this->userResolved = true;

    if ($this->request->bearerToken()) {
        return $this->user = $this->authenticateViaBearerToken();
    }

    if ($this->request->cookie(Passport::cookie())) {
        return $this->user = $this->authenticateViaCookie();
    }

    return null;
}
```

> **Note:** The `client()` method has the same pattern at [TokenGuard.php:98-101](https://github.com/laravel/passport/blob/13.x/src/Guards/TokenGuard.php#L98-L101) and may benefit from the same fix.

## Environment

- PHP 8.5
- Laravel 13.x
- Passport 13.x
