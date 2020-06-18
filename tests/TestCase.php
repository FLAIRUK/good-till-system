<?php

namespace FLAIR\GoodTillSystem\Tests;

use FLAIR\GoodTillSystem\GoodTillSystemFacade;
use FLAIR\GoodTillSystem\GoodTillSystemServiceProvider;
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
