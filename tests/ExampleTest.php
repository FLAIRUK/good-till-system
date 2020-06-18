<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use FLAIRUK\GoodTillSystem\Tests\TestCase;
use FLAIRUK\GoodTillSystem\GoodTillSystemServiceProvider;

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
