<?php

namespace FLAIRUK\GoodTillSystem;

use FLAIRUK\GoodTillSystem\Exceptions\GoodTillException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

/**
 * Goodtill API client.
 *
 * @see https://apidoc.thegoodtill.com
 */
class GoodTill
{
    protected ?string $outletId;

    /**
     * @param  array{base_url: string, timeout: int, retry: array{0: int, 1: int}, outlet_id: ?string}  $config
     */
    public function __construct(
        protected Http $http,
        protected TokenManager $tokens,
        protected array $config,
    ) {
        $this->outletId = $config['outlet_id'] ?: null;
    }

    /**
     * A copy of the client that scopes requests to the given outlet (Outlet-Id header).
     */
    public function forOutlet(?string $outletId): static
    {
        $clone = clone $this;
        $clone->outletId = $outletId;

        return $clone;
    }

    public function outletId(): ?string
    {
        return $this->outletId;
    }

    public function tokens(): TokenManager
    {
        return $this->tokens;
    }

    // ---------------------------------------------------------------------
    // Resources
    // ---------------------------------------------------------------------

    public function brands(): Resources\Brands
    {
        return new Resources\Brands($this);
    }

    public function categories(): Resources\Categories
    {
        return new Resources\Categories($this);
    }

    public function customerGroups(): Resources\CustomerGroups
    {
        return new Resources\CustomerGroups($this);
    }

    public function customers(): Resources\Customers
    {
        return new Resources\Customers($this);
    }

    public function ecommerce(): Resources\Ecommerce
    {
        return new Resources\Ecommerce($this);
    }

    public function externalSales(): Resources\ExternalSales
    {
        return new Resources\ExternalSales($this);
    }

    public function ingredients(): Resources\Ingredients
    {
        return new Resources\Ingredients($this);
    }

    public function outlets(): Resources\Outlets
    {
        return new Resources\Outlets($this);
    }

    public function paymentTypes(): Resources\PaymentTypes
    {
        return new Resources\PaymentTypes($this);
    }

    public function products(): Resources\Products
    {
        return new Resources\Products($this);
    }

    public function promotions(): Resources\Promotions
    {
        return new Resources\Promotions($this);
    }

    public function registers(): Resources\Registers
    {
        return new Resources\Registers($this);
    }

    public function reports(): Resources\Reports
    {
        return new Resources\Reports($this);
    }

    public function sales(): Resources\Sales
    {
        return new Resources\Sales($this);
    }

    public function staff(): Resources\Staff
    {
        return new Resources\Staff($this);
    }

    public function staffClockRecords(): Resources\StaffClockRecords
    {
        return new Resources\StaffClockRecords($this);
    }

    public function suppliers(): Resources\Suppliers
    {
        return new Resources\Suppliers($this);
    }

    public function tags(): Resources\Tags
    {
        return new Resources\Tags($this);
    }

    public function users(): Resources\Users
    {
        return new Resources\Users($this);
    }

    public function vatRates(): Resources\VatRates
    {
        return new Resources\VatRates($this);
    }

    public function vouchers(): Resources\Vouchers
    {
        return new Resources\Vouchers($this);
    }

    // ---------------------------------------------------------------------
    // Raw requests
    // ---------------------------------------------------------------------

    /**
     * The account and store details for the configured credentials.
     */
    public function config(): mixed
    {
        return $this->get('config');
    }

    public function get(string $path, array $query = []): mixed
    {
        return $this->send('GET', $path, ['query' => $query]);
    }

    public function post(string $path, array $data = []): mixed
    {
        return $this->send('POST', $path, ['json' => $data]);
    }

    public function put(string $path, array $data = []): mixed
    {
        return $this->send('PUT', $path, ['json' => $data]);
    }

    public function patch(string $path, array $data = []): mixed
    {
        return $this->send('PATCH', $path, ['json' => $data]);
    }

    public function delete(string $path, array $data = []): mixed
    {
        return $this->send('DELETE', $path, ['json' => $data]);
    }

    /**
     * Send a request and return the response's `data` (or the whole body if there is none).
     *
     * A 401 triggers one fresh login and retry, in case the cached token was revoked.
     *
     * @throws GoodTillException
     */
    public function send(string $method, string $path, array $options = []): mixed
    {
        $options = array_filter($options);
        $method = strtoupper($method);
        $path = ltrim($path, '/');

        $response = $this->prepare($method)->send($method, $path, $options);

        if ($response->status() === 401) {
            $this->tokens->forget();
            $response = $this->prepare($method)->send($method, $path, $options);
        }

        return $this->unwrap($response);
    }

    /**
     * An authenticated request builder, for endpoints this client doesn't wrap.
     */
    public function request(): PendingRequest
    {
        return $this->http->baseUrl($this->config['base_url'])
            ->acceptJson()
            ->asJson()
            ->timeout($this->config['timeout'])
            ->withToken($this->tokens->token())
            ->when($this->outletId, fn (PendingRequest $request, string $id) => $request->withHeaders(['Outlet-Id' => $id]));
    }

    /**
     * Only reads are retried: retrying a write after a timeout or 5xx could, for example, record a sale twice.
     */
    protected function prepare(string $method): PendingRequest
    {
        if ($method !== 'GET') {
            return $this->request();
        }

        [$times, $sleep] = $this->config['retry'];

        return $this->request()->retry($times, $sleep, fn (\Throwable $e) => $e instanceof ConnectionException
            || ($e instanceof RequestException && $e->response->serverError()), throw: false);
    }

    protected function unwrap(Response $response): mixed
    {
        $body = $response->json();

        // Goodtill signals some failures with a 200 and {"status": false, "message": "..."}.
        if ($response->failed() || (is_array($body) && ($body['status'] ?? null) === false)) {
            throw GoodTillException::fromResponse($response);
        }

        return is_array($body) && array_key_exists('data', $body) ? $body['data'] : $body;
    }
}
