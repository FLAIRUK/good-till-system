<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Ingredient
 */
class Ingredients extends Resource
{
    use FindsRecords;
    use ListsRecords;

    protected string $path = 'ingredients';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function inventory(): array
    {
        return $this->client->get($this->path('inventory'));
    }
}
