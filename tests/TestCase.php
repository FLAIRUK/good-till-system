<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use FLAIRUK\GoodTillSystem\Facades\GoodTill;
use FLAIRUK\GoodTillSystem\GoodTillServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected const BASE = 'https://api.thegoodtill.com/api';

    protected function getPackageProviders($app): array
    {
        return [GoodTillServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['GoodTill' => GoodTill::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('cache.default', 'array');
        $app['config']->set('goodtill.subdomain', 'teststore');
        $app['config']->set('goodtill.username', 'api-user');
        $app['config']->set('goodtill.password', 'secret');
        $app['config']->set('goodtill.retry', [1, 0]);
    }

    /**
     * Fake a successful login plus the given endpoint responses.
     */
    protected function fakeApi(array $responses = [], string $token = 'token-1'): void
    {
        Http::preventStrayRequests();

        Http::fake($responses + [
            self::BASE.'/login' => Http::response(['token' => $token, 'user_level' => 'store_owner']),
        ]);
    }
}
