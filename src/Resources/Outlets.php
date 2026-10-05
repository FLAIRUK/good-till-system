<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Outlet
 */
class Outlets extends Resource
{
    use ListsRecords;

    protected string $path = 'outlets';
}
