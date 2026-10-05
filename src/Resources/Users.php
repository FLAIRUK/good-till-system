<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Users
 */
class Users extends Resource
{
    use CreatesRecords;
    use FindsRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'users';

    /**
     * Permissions that admin users can be restricted to.
     *
     * @return array<int, array<string, mixed>>
     */
    public function permissions(): array
    {
        return $this->client->get($this->path('permissions'));
    }
}
