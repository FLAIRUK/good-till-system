<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use DateTimeInterface;

/**
 * Reports are scoped to one outlet: the user's current outlet, or GoodTill::forOutlet($id).
 *
 * @see https://apidoc.thegoodtill.com/#api-Report
 */
class Reports extends Resource
{
    protected string $path = 'report';

    /**
     * @return array<string, mixed>
     */
    public function salesSummary(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->client->post($this->path('sales', 'summary'), $this->range($from, $to));
    }

    /**
     * @return array<string, mixed>
     */
    public function productsSummary(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return $this->client->post($this->path('products', 'summary'), $this->range($from, $to));
    }

    /**
     * Dates are interpreted in the store's timezone.
     *
     * @return array{daterange: string}
     */
    protected function range(DateTimeInterface $from, DateTimeInterface $to): array
    {
        return ['daterange' => $from->format('d/m/Y h:i A').' - '.$to->format('d/m/Y h:i A')];
    }
}
