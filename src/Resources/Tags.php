<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\DeletesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Tag
 */
class Tags extends Resource
{
    use CreatesRecords;
    use DeletesRecords;
    use FindsRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'tags';
}
