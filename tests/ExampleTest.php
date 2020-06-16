<?php

namespace FLAIR\GoodTillSystem\Tests;

use Orchestra\Testbench\TestCase;
use FLAIR\GoodTillSystem\GoodTillSystemServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [GoodTillSystemServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
