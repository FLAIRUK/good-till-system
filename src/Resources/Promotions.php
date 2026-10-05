<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Promotion
 */
class Promotions extends Resource
{
    use ListsRecords;

    protected string $path = 'external/promotions';
}
