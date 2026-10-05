<?php

namespace FLAIRUK\GoodTillSystem\Resources\Concerns;

trait FindsRecords
{
    /**
     * @return array<string, mixed>
     */
    public function find(string $id, array $query = []): array
    {
        return $this->client->get($this->path($id), $query);
    }
}
