<?php

namespace FLAIRUK\GoodTillSystem\Resources\Concerns;

trait ListsRecords
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(array $query = []): array
    {
        return $this->client->get($this->path, $query) ?? [];
    }
}
