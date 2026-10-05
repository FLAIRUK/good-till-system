<?php

namespace FLAIRUK\GoodTillSystem\Resources;

/**
 * Orders sent to the POS for fulfilment (e.g. from a web shop).
 *
 * @see https://apidoc.thegoodtill.com/#api-ExternalSale
 */
class ExternalSales extends Resource
{
    public const ACCEPTED = 'ACCEPTED';

    public const READY = 'READY';

    public const DISPATCHED = 'DISPATCHED';

    public const COMPLETED = 'COMPLETED';

    public const REJECTED = 'REJECTED';

    public const CANCELLED = 'CANCELLED';

    protected string $path = 'external_sale';

    /**
     * Outstanding and recently completed external sales.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(?int $completedHours = null): array
    {
        return $this->client->get($this->path('sales'), array_filter(['completed_hours' => $completedHours]));
    }

    /**
     * @return array<string, mixed>
     */
    public function find(string $id): array
    {
        return $this->client->get($this->path('sale', $id));
    }

    /**
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client->post($this->path('sale'), $data);
    }

    /**
     * Move the order to a new status. Statuses must be set in sequence.
     *
     * @param  array{rejection_reason?: string, cancellation_reason?: string, expected_time?: string}  $extra
     */
    public function updateStatus(string $id, string $status, array $extra = []): mixed
    {
        return $this->client->patch($this->path('sale', $id, 'status'), ['status' => $status] + $extra);
    }

    public function void(string $id): mixed
    {
        return $this->client->post($this->path('sale', $id, 'void'));
    }

    /**
     * Products available to external sales.
     *
     * @return array<int, array<string, mixed>>
     */
    public function products(): array
    {
        return $this->client->get($this->path('products'));
    }
}
