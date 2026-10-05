<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\DeletesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-CustomerGroup
 */
class CustomerGroups extends Resource
{
    use CreatesRecords;
    use DeletesRecords;
    use FindsRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'customerGroups';
}
