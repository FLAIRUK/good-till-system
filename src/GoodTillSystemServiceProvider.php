<?php

namespace FLAIRUK\GoodTillSystem;

use Illuminate\Support\ServiceProvider;
use FLAIRUK\GoodTillSystem\Commands\SetupCommand;

class GoodTillSystemServiceProvider extends ServiceProvider
{
    protected $commands = [
        '\FLAIR\GoodTillSystem\Commands\SetupCommand',
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'good-till-system');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'good-till-system');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('goodtill.php'),
            ], 'config');

            // $this->mergeConfigFrom(
            //     dirname(__DIR__).'/../config/config.php',
            //     config_path('goodtill.php')
            // );


            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/good-till-system'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/good-till-system'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/good-till-system'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'goodtill');

        // Register the main class to use with the facade
        $this->app->singleton('goodtillsystem', function () {
            return new GoodTillSystem;
        });

        if ($this->app->runningInConsole()) {

            $this->registerConsoleCommands();
        }
    }

    /**
     * Register Console Commands
     *
     * @return SetupCommand
     * @return InstallCommand
     * @return LearningLockerCommand
     */
    private function registerConsoleCommands()
    {
        $this->commands(SetupCommand::class);
    }
}
