<?php

namespace FLAIRUK\GoodTillSystem\Resources\Concerns;

trait UpdatesRecords
{
    /**
     * Goodtill updates are full replacements: omitted fields are cleared.
     *
     * @return array<string, mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->client->put($this->path($id), $data);
    }
}
