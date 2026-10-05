<?php

namespace FLAIRUK\GoodTillSystem\Resources;

/**
 * @see https://apidoc.thegoodtill.com/#api-Ecommerce
 */
class Ecommerce extends Resource
{
    protected string $path = 'ecommerce';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function products(array $query = []): array
    {
        return $this->client->get($this->path('products'), $query);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function vatRates(): array
    {
        return $this->client->get($this->path('vat_rates'));
    }

    /**
     * Inventory for specific products (by id or SKU), or all products when both are empty.
     *
     * @param  list<string>  $ids
     * @param  list<string>  $skus
     * @return array<int, array<string, mixed>>
     */
    public function inventory(array $ids = [], array $skus = []): array
    {
        return $this->client->get($this->path('get_inventory'), array_filter([
            'id' => implode(',', $ids),
            'sku' => implode(',', $skus),
        ]));
    }

    /**
     * Decrement (positive quantity) or increment (negative quantity) stock.
     *
     * @param  list<array{id?: string, sku?: string, quantity: int|float}>  $products
     */
    public function adjustInventory(array $products): mixed
    {
        return $this->client->post($this->path('adjust_inventory'), ['products' => $products]);
    }
}
