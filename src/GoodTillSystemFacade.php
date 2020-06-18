<?php

namespace FLAIRUK\GoodTillSystem;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FLAIR\GoodTillSystem\Skeleton\SkeletonClass
 */
class GoodTillSystemFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'goodtillsystem';
    }
}
