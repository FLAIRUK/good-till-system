<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class CommandsTest extends TestCase
{
    #[Test]
    public function install_publishes_config_and_adds_missing_env_keys_once(): void
    {
        $env = $this->app->environmentFilePath();
        $original = File::exists($env) ? File::get($env) : null;
        File::put($env, "APP_NAME=Test\nGOOD_TILL_USERNAME=existing\n");

        try {
            $this->artisan('goodtill:install')->assertSuccessful();
            $this->artisan('goodtill:install')->assertSuccessful();

            $contents = File::get($env);
            $this->assertSame(1, substr_count($contents, 'GOOD_TILL_SUBDOMAIN='));
            $this->assertSame(1, substr_count($contents, 'GOOD_TILL_USERNAME='));
            $this->assertStringContainsString('GOOD_TILL_USERNAME=existing', $contents);
            $this->assertFileExists(config_path('goodtill.php'));
        } finally {
            $original === null ? File::delete($env) : File::put($env, $original);
            File::delete(config_path('goodtill.php'));
        }
    }

    #[Test]
    public function status_lists_outlets(): void
    {
        $this->fakeApi([self::BASE.'/outlets' => Http::response(['status' => true, 'data' => [
            ['id' => 'o1', 'outlet_name' => 'Main Outlet'],
        ]])]);

        $this->artisan('goodtill:status')
            ->expectsTable(['Outlet ID', 'Name'], [['o1', 'Main Outlet']])
            ->assertSuccessful();
    }

    #[Test]
    public function status_reports_failures(): void
    {
        Http::fake(['*/login' => Http::response(['message' => 'Invalid credentials'], 401)]);

        $this->artisan('goodtill:status')->assertFailed();
    }

    #[Test]
    public function status_reports_an_unreachable_host(): void
    {
        Http::fake(fn () => throw new ConnectionException('Connection timed out'));

        $this->artisan('goodtill:status')
            ->expectsOutputToContain('Could not reach Goodtill')
            ->assertFailed();
    }

    #[Test]
    public function the_legacy_env_variable_name_is_still_read(): void
    {
        $config = require __DIR__.'/../config/goodtill.php';
        $this->assertArrayHasKey('subdomain', $config);

        putenv('GOOD_TILL_DOAMIN=legacy');
        try {
            $this->assertSame('legacy', (require __DIR__.'/../config/goodtill.php')['subdomain']);
        } finally {
            putenv('GOOD_TILL_DOAMIN');
        }
    }

    #[Test]
    public function an_empty_subdomain_falls_back_to_the_legacy_name(): void
    {
        // goodtill:install writes an empty GOOD_TILL_SUBDOMAIN, which must not hide the old variable.
        putenv('GOOD_TILL_SUBDOMAIN=');
        putenv('GOOD_TILL_DOAMIN=legacy');
        try {
            $this->assertSame('legacy', (require __DIR__.'/../config/goodtill.php')['subdomain']);
        } finally {
            putenv('GOOD_TILL_SUBDOMAIN');
            putenv('GOOD_TILL_DOAMIN');
        }
    }
}
