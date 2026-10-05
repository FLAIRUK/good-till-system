<?php

namespace FLAIRUK\GoodTillSystem\Resources\Concerns;

trait DeletesRecords
{
    public function delete(string $id): bool
    {
        $this->client->delete($this->path('delete', $id));

        return true;
    }
}
