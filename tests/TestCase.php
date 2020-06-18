<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use FLAIRUK\GoodTillSystem\GoodTillSystemFacade;
use FLAIRUK\GoodTillSystem\GoodTillSystemServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
      parent::setUp();
    }
    
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return GoodTillSystemServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            GoodTillSystemServiceProvider::class
        ];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'GoodTillSystem' => GoodTillSystemFacade::class,
        ];
    }
}
