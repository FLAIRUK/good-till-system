<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use FLAIRUK\GoodTillSystem\Exceptions\AuthenticationException;
use FLAIRUK\GoodTillSystem\Facades\GoodTill;
use FLAIRUK\GoodTillSystem\TokenManager;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class AuthenticationTest extends TestCase
{
    #[Test]
    public function it_logs_in_once_and_reuses_the_cached_token(): void
    {
        $this->fakeApi([self::BASE.'/outlets' => Http::response(['status' => true, 'data' => []])]);

        GoodTill::outlets()->all();
        GoodTill::outlets()->all();

        Http::assertSentCount(3);
        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/login'
            && $request['subdomain'] === 'teststore'
            && $request['username'] === 'api-user'
            && $request['password'] === 'secret');
        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/outlets'
            && $request->hasHeader('Authorization', 'Bearer token-1'));
    }

    #[Test]
    public function it_refreshes_the_token_once_it_is_older_than_the_refresh_threshold(): void
    {
        $this->fakeApi([
            self::BASE.'/refresh_token' => Http::response(['success' => '1', 'token' => 'token-2']),
            self::BASE.'/outlets' => Http::response(['status' => true, 'data' => []]),
        ]);

        GoodTill::outlets()->all();
        $this->travel(11 * 60 + 1)->minutes();
        GoodTill::outlets()->all();

        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/refresh_token'
            && $request->hasHeader('Authorization', 'Bearer token-1'));
        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/outlets'
            && $request->hasHeader('Authorization', 'Bearer token-2'));
    }

    #[Test]
    public function it_logs_in_again_when_the_refresh_is_refused(): void
    {
        Http::fake([
            self::BASE.'/login' => Http::sequence()
                ->push(['token' => 'token-1'])
                ->push(['token' => 'token-3']),
            self::BASE.'/refresh_token' => Http::response(['message' => 'Token expired'], 401),
            self::BASE.'/outlets' => Http::response(['status' => true, 'data' => []]),
        ]);

        GoodTill::outlets()->all();
        $this->travel(3)->days();
        GoodTill::outlets()->all();

        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/outlets'
            && $request->hasHeader('Authorization', 'Bearer token-3'));
    }

    #[Test]
    public function a_401_discards_the_token_and_retries_once_with_a_fresh_login(): void
    {
        Http::fake([
            self::BASE.'/login' => Http::sequence()
                ->push(['token' => 'revoked'])
                ->push(['token' => 'token-2']),
            self::BASE.'/outlets' => Http::sequence()
                ->push(['message' => 'Unauthorised'], 401)
                ->push(['status' => true, 'data' => [['id' => 'o1']]]),
        ]);

        $this->assertSame([['id' => 'o1']], GoodTill::outlets()->all());
        Http::assertSentCount(4);
    }

    #[Test]
    public function it_fails_clearly_when_credentials_are_missing(): void
    {
        config(['goodtill.password' => null]);
        $this->app->forgetInstance(TokenManager::class);
        $this->app->forgetInstance(\FLAIRUK\GoodTillSystem\GoodTill::class);
        GoodTill::clearResolvedInstances();
        Http::preventStrayRequests();

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('GOOD_TILL_PASSWORD');

        GoodTill::outlets()->all();
    }

    #[Test]
    public function it_rejects_bad_credentials(): void
    {
        Http::fake([self::BASE.'/login' => Http::response(['message' => 'Invalid credentials'], 401)]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials');

        GoodTill::outlets()->all();
    }

    #[Test]
    public function operator_accounts_are_rejected(): void
    {
        Http::fake([self::BASE.'/login' => Http::response(['token' => 'x', 'user_level' => 'operator'])]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('operator');

        GoodTill::outlets()->all();
    }

    #[Test]
    public function logout_invalidates_the_token(): void
    {
        $this->fakeApi([
            self::BASE.'/outlets' => Http::response(['status' => true, 'data' => []]),
            self::BASE.'/logout' => Http::response(['success' => 1]),
        ]);

        GoodTill::outlets()->all();
        GoodTill::tokens()->logout();
        GoodTill::outlets()->all();

        Http::assertSent(fn (Request $request) => $request->url() === self::BASE.'/logout');
        $this->assertCount(2, Http::recorded(fn (Request $request) => $request->url() === self::BASE.'/login'));
    }
}
