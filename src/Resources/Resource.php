<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\GoodTill;

abstract class Resource
{
    /**
     * Endpoint path relative to the API base URL, e.g. "products".
     */
    protected string $path;

    public function __construct(protected GoodTill $client) {}

    protected function path(string ...$segments): string
    {
        return implode('/', [$this->path, ...array_map('rawurlencode', $segments)]);
    }
}
