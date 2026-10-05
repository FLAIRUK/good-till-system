<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-StaffClockRecord
 */
class StaffClockRecords extends Resource
{
    use CreatesRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'staff_clock_records';

    /**
     * Goodtill fetches a single record with POST rather than GET.
     *
     * @return array<string, mixed>
     */
    public function find(string $id): array
    {
        return $this->client->post($this->path($id));
    }
}
