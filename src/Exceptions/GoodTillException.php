<?php

namespace FLAIRUK\GoodTillSystem\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class GoodTillException extends RuntimeException
{
    public function __construct(string $message, public readonly ?Response $response = null)
    {
        parent::__construct($message, $response?->status() ?? 0);
    }

    public static function fromResponse(Response $response): self
    {
        $message = $response->json('message') ?: $response->json('error') ?: $response->reason();

        return new self("Goodtill API request failed ({$response->status()}): {$message}", $response);
    }
}
