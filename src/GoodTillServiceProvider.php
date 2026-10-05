<?php

namespace FLAIRUK\GoodTillSystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Support\ServiceProvider;

class GoodTillServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/goodtill.php', 'goodtill');

        $this->app->singleton(TokenManager::class, fn (Application $app) => new TokenManager(
            $app->make(Http::class),
            $app['cache']->store($app['config']->get('goodtill.cache_store')),
            $app['config']->get('goodtill'),
        ));

        $this->app->singleton(GoodTill::class, fn (Application $app) => new GoodTill(
            $app->make(Http::class),
            $app->make(TokenManager::class),
            $app['config']->get('goodtill'),
        ));

        $this->app->alias(GoodTill::class, 'goodtill');
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/goodtill.php' => config_path('goodtill.php'),
        ], 'goodtill-config');

        $this->commands([
            Console\InstallCommand::class,
            Console\StatusCommand::class,
        ]);
    }
}
