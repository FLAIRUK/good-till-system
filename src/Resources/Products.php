<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\DeletesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\FindsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Product
 */
class Products extends Resource
{
    use CreatesRecords;
    use DeletesRecords;
    use FindsRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'products';

    /**
     * Stock levels of every tracked product.
     *
     * @return array<int, array<string, mixed>>
     */
    public function inventory(): array
    {
        return $this->client->get($this->path('inventory'));
    }

    /**
     * Copy a product (with its variants), or a variant within its parent.
     *
     * @return array<string, mixed>
     */
    public function duplicate(string $id): array
    {
        return $this->client->post($this->path($id, 'duplicate'));
    }

    /**
     * @return array<string, mixed>
     */
    public function createVariant(string $parentId, array $data): array
    {
        return $this->client->post($this->path('variants'), ['parent_product_id' => $parentId] + $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function updateVariant(string $id, array $data): array
    {
        return $this->client->put($this->path('variants', $id), $data);
    }
}
