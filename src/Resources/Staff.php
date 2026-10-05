<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Staff
 */
class Staff extends Resource
{
    use CreatesRecords;
    use FindsRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'staffs';
}
