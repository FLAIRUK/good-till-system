<?php

namespace FLAIRUK\GoodTillSystem;

use FLAIRUK\GoodTillSystem\Exceptions\AuthenticationException;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\Response;

/**
 * Obtains, caches and refreshes the Goodtill JWT.
 *
 * Tokens expire 12 hours after creation and can be refreshed for up to two
 * weeks, so a cached token is refreshed once it is older than
 * `refresh_after_minutes`, and a fresh login is made if refreshing fails.
 */
class TokenManager
{
    protected const REFRESH_WINDOW_DAYS = 14;

    /**
     * @param  array{subdomain: ?string, username: ?string, password: ?string, base_url: string, timeout: int, refresh_after_minutes: int}  $config
     */
    public function __construct(
        protected Http $http,
        protected Cache $cache,
        protected array $config,
    ) {}

    public function token(): string
    {
        $cached = $this->cache->get($this->cacheKey());

        if (! is_array($cached)) {
            return $this->login();
        }

        if (now()->getTimestamp() - $cached['issued_at'] >= $this->config['refresh_after_minutes'] * 60) {
            return $this->refresh($cached['token']) ?? $this->login();
        }

        return $cached['token'];
    }

    /**
     * Log in with the configured credentials and cache the new token.
     *
     * @throws AuthenticationException
     */
    public function login(): string
    {
        $this->ensureCredentials();

        $response = $this->http->baseUrl($this->config['base_url'])
            ->acceptJson()
            ->timeout($this->config['timeout'])
            ->post('login', [
                'subdomain' => $this->config['subdomain'],
                'username' => $this->config['username'],
                'password' => $this->config['password'],
            ]);

        $token = $response->json('token');

        if ($response->failed() || ! is_string($token) || $token === '') {
            throw new AuthenticationException($this->failureMessage($response, 'Goodtill login failed'), $response);
        }

        if ($response->json('user_level') === 'operator') {
            throw new AuthenticationException('Goodtill operator users cannot use the API; use a store owner or admin account.', $response);
        }

        return $this->store($token);
    }

    /**
     * Exchange a token for a new one. Returns null if Goodtill refuses.
     */
    public function refresh(string $token): ?string
    {
        $response = $this->http->baseUrl($this->config['base_url'])
            ->acceptJson()
            ->timeout($this->config['timeout'])
            ->withToken($token)
            ->get('refresh_token');

        $refreshed = $response->json('token');

        if ($response->failed() || ! is_string($refreshed) || $refreshed === '') {
            $this->forget();

            return null;
        }

        return $this->store($refreshed);
    }

    /**
     * Invalidate the cached token with Goodtill and forget it locally.
     */
    public function logout(): void
    {
        $cached = $this->cache->get($this->cacheKey());

        if (is_array($cached)) {
            $this->http->baseUrl($this->config['base_url'])
                ->acceptJson()
                ->timeout($this->config['timeout'])
                ->withToken($cached['token'])
                ->post('logout');
        }

        $this->forget();
    }

    public function forget(): void
    {
        $this->cache->forget($this->cacheKey());
    }

    protected function store(string $token): string
    {
        $this->cache->put(
            $this->cacheKey(),
            ['token' => $token, 'issued_at' => now()->getTimestamp()],
            now()->addDays(self::REFRESH_WINDOW_DAYS),
        );

        return $token;
    }

    protected function cacheKey(): string
    {
        return 'goodtill:token:'.sha1(strtolower((string) $this->config['subdomain']).'|'.$this->config['username']);
    }

    protected function ensureCredentials(): void
    {
        foreach (['subdomain', 'username', 'password'] as $key) {
            if (blank($this->config[$key] ?? null)) {
                throw new AuthenticationException(
                    "Goodtill {$key} is not configured. Set GOOD_TILL_".strtoupper($key).' in your .env file.'
                );
            }
        }
    }

    protected function failureMessage(Response $response, string $prefix): string
    {
        $detail = $response->json('message') ?: $response->json('error') ?: $response->reason();

        return "{$prefix} ({$response->status()}): {$detail}";
    }
}
