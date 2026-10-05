<?php

namespace FLAIRUK\GoodTillSystem\Resources\Concerns;

trait CreatesRecords
{
    /**
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post($this->path, $data);
    }
}
