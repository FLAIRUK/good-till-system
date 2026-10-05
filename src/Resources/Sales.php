<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use DateTimeInterface;

/**
 * @see https://apidoc.thegoodtill.com/#api-Sale
 */
class Sales extends Resource
{
    protected string $path = 'sales';

    /**
     * @return array<string, mixed>
     */
    public function find(string $id): array
    {
        return $this->client->get($this->path($id));
    }

    /**
     * Record an already-fulfilled sale from an external system.
     *
     * To send an order to the POS for fulfilment use externalSales()->create() instead.
     *
     * @return array{sale_id: string}
     */
    public function create(array $data): array
    {
        return $this->client->post('sale', $data);
    }

    /**
     * Sales across all accessible outlets in a date range.
     *
     * @param  array{timezone?: 'utc'|'local', include_voided?: int, outlet_ids?: list<string>}  $options
     * @return array<int, array<string, mixed>>
     */
    public function between(DateTimeInterface $from, DateTimeInterface $to, array $options = []): array
    {
        return $this->client->get('external/get_sales', $this->range($from, $to) + $options);
    }

    /**
     * Full sale details in a date range, paginated (max 50 per page).
     *
     * @param  array{timezone?: 'utc'|'local', include_voided?: int, outlet_ids?: list<string>}  $options
     * @return array<int, array<string, mixed>>
     */
    public function detailsBetween(DateTimeInterface $from, DateTimeInterface $to, int $limit = 50, int $offset = 0, array $options = []): array
    {
        return $this->client->get('external/get_sales_details', $this->range($from, $to) + [
            'limit' => min($limit, 50),
            'offset' => $offset,
        ] + $options);
    }

    /**
     * Lazily iterate every sale's details in a date range, fetching 50 at a time.
     *
     * @return \Generator<int, array<string, mixed>>
     */
    public function eachDetailBetween(DateTimeInterface $from, DateTimeInterface $to, array $options = []): \Generator
    {
        $offset = 0;

        do {
            $page = $this->detailsBetween($from, $to, 50, $offset, $options);

            yield from $page;

            $offset += 50;
        } while (count($page) === 50);
    }

    /**
     * @return array{from: string, to: string}
     */
    protected function range(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return ['from' => $from->format('Y-m-d H:i:s'), 'to' => $to->format('Y-m-d H:i:s')];
    }
}
