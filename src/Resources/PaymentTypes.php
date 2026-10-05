<?php

namespace FLAIRUK\GoodTillSystem\Resources;

/**
 * @see https://apidoc.thegoodtill.com/#api-PaymentType
 */
class PaymentTypes extends Resource
{
    protected string $path = 'ajax';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->client->get($this->path('payment_types')) ?? [];
    }

    /**
     * @param  array{name: string, description?: string}  $data
     */
    public function create(array $data): mixed
    {
        return $this->client->post($this->path('adjust_payment_types'), $data);
    }

    public function update(string $id, array $data): mixed
    {
        return $this->client->post($this->path('adjust_payment_types'), ['id' => $id] + $data);
    }

    public function activate(string $id, bool $active = true): mixed
    {
        return $this->update($id, ['active' => $active ? '1' : '0']);
    }

    public function deactivate(string $id): mixed
    {
        return $this->activate($id, false);
    }
}
