<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\ListsRecords;
use FLAIRUK\GoodTillSystem\Resources\Concerns\UpdatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Customer
 */
class Customers extends Resource
{
    use CreatesRecords;
    use ListsRecords;
    use UpdatesRecords;

    protected string $path = 'customers';

    /**
     * @return array<string, mixed>
     */
    public function find(string $id): array
    {
        return $this->client->get($this->path('details', $id));
    }

    /**
     * Sales assigned to the customer.
     *
     * @return array<int, array<string, mixed>>
     */
    public function sales(string $id): array
    {
        return $this->client->get($this->path($id, 'sales'));
    }

    /**
     * Add (or with a negative amount, remove) points from a customer's loyalty balance.
     */
    public function adjustLoyaltyPoints(string $id, int $points, ?string $description = null): mixed
    {
        return $this->client->post('loyalty/assign_points/'.rawurlencode($id), array_filter([
            'points' => $points,
            'description' => $description,
        ], fn ($value) => $value !== null));
    }

    /**
     * Add a prepayment to an account customer's balance.
     *
     * @param  array{payment_amount: string, payment_date?: string, payment_ref?: string, payment_type?: string}  $data
     */
    public function addPrepayment(string $id, array $data): mixed
    {
        return $this->client->post($this->path('add_prepayment', $id), $data);
    }
}
