<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Register
 */
class Registers extends Resource
{
    use ListsRecords;

    protected string $path = 'registers';
}
