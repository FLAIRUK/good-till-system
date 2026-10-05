<?php

namespace FLAIRUK\GoodTillSystem\Tests;

use Carbon\CarbonImmutable;
use FLAIRUK\GoodTillSystem\Exceptions\GoodTillException;
use FLAIRUK\GoodTillSystem\Facades\GoodTill;
use FLAIRUK\GoodTillSystem\Resources\ExternalSales;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class ResourcesTest extends TestCase
{
    protected function ok(mixed $data = []): PromiseInterface
    {
        return Http::response(['status' => true, 'data' => $data]);
    }

    #[Test]
    public function product_crud_hits_the_documented_endpoints(): void
    {
        $this->fakeApi([
            self::BASE.'/products/delete/p1' => $this->ok(['success' => 1]),
            self::BASE.'/products/variants' => $this->ok(['id' => 'v1']),
            self::BASE.'/products/p1' => $this->ok(['id' => 'p1']),
            self::BASE.'/products' => $this->ok([['id' => 'p1']]),
        ]);

        $this->assertSame([['id' => 'p1']], GoodTill::products()->all());
        $this->assertSame(['id' => 'p1'], GoodTill::products()->find('p1'));
        GoodTill::products()->create(['product_name' => 'Pilsner']);
        GoodTill::products()->update('p1', ['product_name' => 'Lager']);
        $this->assertTrue(GoodTill::products()->delete('p1'));
        GoodTill::products()->createVariant('p1', ['product_name' => 'Pint']);

        Http::assertSent(fn (Request $r) => $r->method() === 'POST' && $r->url() === self::BASE.'/products' && $r['product_name'] === 'Pilsner');
        Http::assertSent(fn (Request $r) => $r->method() === 'PUT' && $r->url() === self::BASE.'/products/p1' && $r['product_name'] === 'Lager');
        Http::assertSent(fn (Request $r) => $r->method() === 'DELETE' && $r->url() === self::BASE.'/products/delete/p1');
        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/products/variants' && $r['parent_product_id'] === 'p1');
    }

    #[Test]
    public function the_outlet_header_comes_from_config_or_for_outlet(): void
    {
        config(['goodtill.outlet_id' => 'default-outlet']);
        $this->app->forgetInstance(\FLAIRUK\GoodTillSystem\GoodTill::class);
        GoodTill::clearResolvedInstances();
        $this->fakeApi([self::BASE.'/products' => $this->ok()]);

        GoodTill::products()->all();
        GoodTill::forOutlet('other-outlet')->products()->all();
        GoodTill::products()->all();

        $outlets = Http::recorded(fn (Request $r) => $r->url() === self::BASE.'/products')
            ->map(fn (array $pair) => $pair[0]->header('Outlet-Id')[0] ?? null)
            ->values()
            ->all();

        $this->assertSame(['default-outlet', 'other-outlet', 'default-outlet'], $outlets);
    }

    #[Test]
    public function api_errors_reported_in_the_body_throw(): void
    {
        $this->fakeApi([self::BASE.'/sale' => Http::response(['status' => false, 'message' => 'The selected payments.1.method is invalid.'])]);

        $this->expectException(GoodTillException::class);
        $this->expectExceptionMessage('The selected payments.1.method is invalid.');

        GoodTill::sales()->create(['sales_items' => []]);
    }

    #[Test]
    public function http_errors_throw_with_the_response_attached(): void
    {
        $this->fakeApi([self::BASE.'/products/missing' => Http::response(['message' => 'Not found'], 404)]);

        try {
            GoodTill::products()->find('missing');
            $this->fail('Expected exception');
        } catch (GoodTillException $e) {
            $this->assertSame(404, $e->getCode());
            $this->assertSame(404, $e->response->status());
        }
    }

    #[Test]
    public function customers_use_the_details_endpoint_and_loyalty_helpers(): void
    {
        $this->fakeApi([
            self::BASE.'/customers/details/c1' => $this->ok(['id' => 'c1']),
            self::BASE.'/customers/c1/sales' => $this->ok([]),
            self::BASE.'/loyalty/assign_points/c1' => $this->ok(),
        ]);

        $this->assertSame(['id' => 'c1'], GoodTill::customers()->find('c1'));
        GoodTill::customers()->sales('c1');
        GoodTill::customers()->adjustLoyaltyPoints('c1', 50, 'Bonus');

        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/loyalty/assign_points/c1' && $r['points'] === 50 && $r['description'] === 'Bonus');
    }

    #[Test]
    public function sales_and_reports_format_date_ranges(): void
    {
        $this->fakeApi([
            self::BASE.'/external/get_sales*' => $this->ok([]),
            self::BASE.'/report/sales/summary' => $this->ok(['total' => 1]),
        ]);

        $from = CarbonImmutable::parse('2026-01-29 00:00:00');
        $to = CarbonImmutable::parse('2026-01-29 23:59:59');

        GoodTill::sales()->between($from, $to, ['timezone' => 'utc']);
        GoodTill::reports()->salesSummary($from, $to);

        Http::assertSent(fn (Request $r) => str_starts_with($r->url(), self::BASE.'/external/get_sales?')
            && $r['from'] === '2026-01-29 00:00:00' && $r['to'] === '2026-01-29 23:59:59' && $r['timezone'] === 'utc');
        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/report/sales/summary'
            && $r['daterange'] === '29/01/2026 12:00 AM - 29/01/2026 11:59 PM');
    }

    #[Test]
    public function sale_details_are_paged_lazily(): void
    {
        $page = fn (int $n) => array_map(fn ($i) => ['sales_id' => "s{$i}"], range(1, $n));

        $this->fakeApi([
            self::BASE.'/external/get_sales_details*' => Http::sequence()
                ->push(['status' => true, 'data' => $page(50)])
                ->push(['status' => true, 'data' => $page(7)]),
        ]);

        $sales = iterator_to_array(GoodTill::sales()->eachDetailBetween(now()->subDay(), now()), false);

        $this->assertCount(57, $sales);
        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'get_sales_details') && (int) $r['offset'] === 50 && (int) $r['limit'] === 50);
    }

    #[Test]
    public function external_sales_status_and_void(): void
    {
        $this->fakeApi([
            self::BASE.'/external_sale/sale/s1/status' => $this->ok(),
            self::BASE.'/external_sale/sale/s1/void' => $this->ok(),
        ]);

        GoodTill::externalSales()->updateStatus('s1', ExternalSales::REJECTED, ['rejection_reason' => 'Out of stock']);
        GoodTill::externalSales()->void('s1');

        Http::assertSent(fn (Request $r) => $r->method() === 'PATCH' && $r['status'] === 'REJECTED' && $r['rejection_reason'] === 'Out of stock');
        Http::assertSent(fn (Request $r) => $r->method() === 'POST' && $r->url() === self::BASE.'/external_sale/sale/s1/void');
    }

    #[Test]
    public function ecommerce_inventory_helpers(): void
    {
        $this->fakeApi([
            self::BASE.'/ecommerce/get_inventory*' => $this->ok([]),
            self::BASE.'/ecommerce/adjust_inventory' => $this->ok(),
        ]);

        GoodTill::ecommerce()->inventory(skus: ['a', 'b']);
        GoodTill::ecommerce()->adjustInventory([['sku' => 'a', 'quantity' => 2]]);

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'get_inventory') && $r['sku'] === 'a,b' && ! isset($r['id']));
        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/ecommerce/adjust_inventory' && $r['products'] === [['sku' => 'a', 'quantity' => 2]]);
    }

    #[Test]
    public function vat_rates_use_the_ajax_endpoints(): void
    {
        $this->fakeApi([
            self::BASE.'/ajax/vat_rates' => $this->ok([['id' => 'v1']]),
            self::BASE.'/ajax/adjust_vat_rates' => $this->ok(),
        ]);

        $this->assertSame([['id' => 'v1']], GoodTill::vatRates()->all());
        GoodTill::vatRates()->deactivate('v1');

        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/ajax/adjust_vat_rates' && $r['id'] === 'v1' && $r['active'] === '0');
    }

    #[Test]
    public function ids_are_url_encoded(): void
    {
        $this->fakeApi([self::BASE.'/tags/*' => $this->ok()]);

        GoodTill::tags()->find('a/b');

        Http::assertSent(fn (Request $r) => $r->url() === self::BASE.'/tags/a%2Fb');
    }

    #[Test]
    public function reads_are_retried_on_server_errors_but_writes_are_not(): void
    {
        config(['goodtill.retry' => [3, 0]]);
        $this->app->forgetInstance(\FLAIRUK\GoodTillSystem\GoodTill::class);
        GoodTill::clearResolvedInstances();

        $this->fakeApi([
            self::BASE.'/outlets' => Http::sequence()->push([], 503)->push(['status' => true, 'data' => [['id' => 'o1']]]),
            self::BASE.'/sale' => Http::response([], 503),
        ]);

        $this->assertSame([['id' => 'o1']], GoodTill::outlets()->all());

        try {
            GoodTill::sales()->create(['sales_items' => []]);
            $this->fail('Expected exception');
        } catch (GoodTillException $e) {
            $this->assertSame(503, $e->getCode());
        }

        $this->assertCount(1, Http::recorded(fn (Request $r) => $r->url() === self::BASE.'/sale'));
    }
}
