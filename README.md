<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="art/logo-dark.svg">
    <img src="art/logo-light.svg" alt="Goodtill for Laravel" width="420">
  </picture>
</p>

<h2 align="center">
  <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2+"></a>&nbsp;
  <a href="https://laravel.com/docs/" target="_blank"><img src="https://img.shields.io/badge/Laravel-12%20%7C%2013-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12 or 13"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/good-till-system/actions/workflows/tests.yml" target="_blank"><img src="https://img.shields.io/badge/Lint-%E2%9C%93-2EA043?style=flat&logo=githubactions&logoColor=white" alt="Lint"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/good-till-system/actions/workflows/tests.yml" target="_blank"><img src="https://img.shields.io/badge/Tests-%E2%9C%93-2EA043?style=flat&logo=githubactions&logoColor=white" alt="Tests"></a>&nbsp;
  <a href="https://packagist.org/packages/flairuk/good-till-system" target="_blank"><img src="https://img.shields.io/packagist/dt/flairuk/good-till-system?style=flat&logo=packagist&logoColor=white&label=Downloads&color=F28D1A" alt="Downloads on Packagist"></a>&nbsp;
  <a href="https://github.com/FLAIRUK/good-till-system/blob/master/LICENSE.md" target="_blank"><img src="https://img.shields.io/github/license/FLAIRUK/good-till-system?style=flat&label=License&color=3DA639" alt="MIT licence"></a>&nbsp;
  <a href="https://apidoc.thegoodtill.com" target="_blank"><img src="https://img.shields.io/badge/Client-Goodtill%20API-BE123C?style=flat" alt="Goodtill API"></a>&nbsp;
  <br>&nbsp;
</h2>

**Goodtill for Laravel** — A Laravel 12 and 13 client for the [Goodtill EPOS API](https://apidoc.thegoodtill.com).

- **Automatic authentication.** It logs in once and caches the JWT. It refreshes the token before the 12-hour expiry and logs in again if Goodtill revokes it.
- **Resource classes.** Products, customers, sales, reports, external (web) orders, stock and more, with the endpoint quirks handled for you.
- **Real errors.** Failures throw `GoodTillException`, including the `200 {"status": false}` responses Goodtill sometimes returns.
- **Safe retries.** Reads are retried on connection errors and 5xx responses. Writes are never retried automatically, so a sale is never recorded twice.
- **Multi-outlet.** Use `GoodTill::forOutlet($id)` to work with any outlet.

<p align="center">
  📦&nbsp;<a href="#-installation">Installation</a> ·
  🚀&nbsp;<a href="#-usage">Usage</a> ·
  🔌&nbsp;<a href="#-testing-your-integration">Testing your integration</a> ·
  🔄&nbsp;<a href="#-upgrading-from-dev-master">Upgrading</a>
</p>

<br><br>

## 📦 Installation

```bash
composer require flairuk/good-till-system
php artisan goodtill:install
```

`goodtill:install` publishes `config/goodtill.php` and adds any of these keys that are missing to `.env` and `.env.example`, empty. Fill them in:

```dotenv
GOOD_TILL_SUBDOMAIN=yourstore
GOOD_TILL_USERNAME=api-user
GOOD_TILL_PASSWORD=secret
GOOD_TILL_OUTLET_ID=        # optional default outlet
```

Then check the connection:

```bash
php artisan goodtill:status
```

> Goodtill only allows **store owner** or **admin** users to use the API. Create a dedicated user for the integration, so that "log out of all sessions" in the back office doesn't break it. You can request a test account from dev@thegoodtill.com.

If you run several servers or queue workers, set `GOOD_TILL_CACHE_STORE` to a shared cache such as `redis` or `database` so they all share one token.

<br><br>

## 🚀 Usage

```php
use FLAIRUK\GoodTillSystem\Facades\GoodTill;
```

You can also type-hint `FLAIRUK\GoodTillSystem\GoodTill` to have it injected.

Each method returns the `data` from Goodtill's response as an array (`delete()` returns `true`).

### Products

```php
GoodTill::products()->all();
GoodTill::products()->find($id);
GoodTill::products()->create([
    'outlet_id' => $outletId,
    'vat_code_id' => $vatRateId,
    'product_name' => 'Berliner Pilsner',
    'selling_price' => 4.75,
]);
GoodTill::products()->update($id, $data);   // full replacement: omitted fields are cleared
GoodTill::products()->delete($id);
GoodTill::products()->duplicate($id);
GoodTill::products()->createVariant($parentId, $data);
GoodTill::products()->inventory();
```

`brands()`, `categories()`, `tags()`, `suppliers()` and `customerGroups()` support the same `all / find / create / update / delete` methods. `staff()` and `users()` support everything except `delete`.

### Customers

```php
GoodTill::customers()->all();
GoodTill::customers()->find($id);
GoodTill::customers()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
GoodTill::customers()->update($id, $data);
GoodTill::customers()->sales($id);
GoodTill::customers()->adjustLoyaltyPoints($id, 50, 'Online order bonus');
GoodTill::customers()->addPrepayment($id, ['payment_amount' => '20.00']);
```

### Sales and reports

```php
// Record a sale that has already been fulfilled elsewhere
GoodTill::sales()->create([
    'sales_items' => [['product_id' => $id, 'price' => '4.75', 'quantity' => 2]],
    'payments' => [['method' => 'CARD', 'amount' => '9.50']],
]);

GoodTill::sales()->find($saleId);
GoodTill::sales()->between(now()->startOfDay(), now(), ['timezone' => 'utc']);

// Fetches full sale details in pages of 50, lazily
foreach (GoodTill::sales()->eachDetailBetween(now()->subWeek(), now()) as $sale) {
    // ...
}

GoodTill::reports()->salesSummary(now()->startOfDay(), now());
GoodTill::reports()->productsSummary(now()->startOfMonth(), now());
```

### External orders (web shop → POS)

```php
use FLAIRUK\GoodTillSystem\Resources\ExternalSales;

$order = GoodTill::externalSales()->create($data);   // appears on the POS for fulfilment
GoodTill::externalSales()->all();                    // outstanding and recently completed orders
GoodTill::externalSales()->find($id);
GoodTill::externalSales()->updateStatus($id, ExternalSales::READY);
GoodTill::externalSales()->updateStatus($id, ExternalSales::REJECTED, ['rejection_reason' => 'Out of stock']);
GoodTill::externalSales()->void($id);
GoodTill::externalSales()->products();
```

### E-commerce stock sync

```php
GoodTill::ecommerce()->products();
GoodTill::ecommerce()->inventory(skus: ['tshirt-med-green']);
GoodTill::ecommerce()->adjustInventory([
    ['sku' => 'tshirt-med-green', 'quantity' => 1],    // positive decrements stock
]);
```

### Everything else

```php
GoodTill::outlets()->all();
GoodTill::registers()->all();
GoodTill::vatRates()->all();
GoodTill::vatRates()->create(['vat_name' => 'Standard', 'vat_rate' => 20]);
GoodTill::paymentTypes()->all();
GoodTill::staffClockRecords()->create(['staff_id' => $id, 'clock_in' => now()->toDateTimeString()]);
GoodTill::ingredients()->inventory();
GoodTill::promotions()->all();
GoodTill::vouchers()->create(['code' => 'GIFT50', 'amount' => '50.00']);
GoodTill::config();                                   // store / account settings
```

For an endpoint without a wrapper, call it directly. Authentication and outlet headers are still handled:

```php
GoodTill::get('some/endpoint', ['query' => 'value']);
GoodTill::post('some/endpoint', $data);
GoodTill::request();   // a configured Illuminate PendingRequest
```

### Outlets

Requests use the user's current outlet, or `GOOD_TILL_OUTLET_ID` if it is set. To scope calls to another outlet:

```php
GoodTill::forOutlet($outletId)->reports()->salesSummary($from, $to);
```

### Errors

```php
use FLAIRUK\GoodTillSystem\Exceptions\AuthenticationException;
use FLAIRUK\GoodTillSystem\Exceptions\GoodTillException;

try {
    GoodTill::sales()->create($data);
} catch (AuthenticationException $e) {
    // bad credentials, operator account, or missing config
} catch (GoodTillException $e) {
    $e->getMessage();          // includes Goodtill's message, e.g. "The selected payments.1.method is invalid."
    $e->response?->json();     // the full response
}
```

### Tokens

```php
GoodTill::tokens()->logout();   // invalidate the token with Goodtill, e.g. when disconnecting an account
GoodTill::tokens()->forget();   // drop the cached token locally
```

<br><br>

## 🔌 Testing your integration

The client uses Laravel's HTTP client, so `Http::fake()` works in your own tests:

```php
Http::fake([
    'api.thegoodtill.com/api/login' => Http::response(['token' => 'test']),
    'api.thegoodtill.com/api/products' => Http::response(['status' => true, 'data' => []]),
]);
```

<br><br>

## 🔄 Upgrading from dev-master

Version 1 is a rewrite. The previous version could not make most API calls (it logged in on every request and several classes were missing), so the API has changed:

| Before | Now |
| --- | --- |
| `GoodTillSystem::products()->get()` | `GoodTill::products()->all()` |
| `GoodTillSystem::product($id)->get()` | `GoodTill::products()->find($id)` |
| `GoodTillSystem::products()->create($data)` | `GoodTill::products()->create($data)` |
| `GoodTillSystem::product($id)->update($data)` | `GoodTill::products()->update($id, $data)` |
| `GoodTillSystem::product($id)->delete()` | `GoodTill::products()->delete($id)` |
| Facade alias `GoodTillSystem` | `GoodTill` (`FLAIRUK\GoodTillSystem\Facades\GoodTill`) |
| Config `goodtill.authorize.*`, `goodtill.routes.*` | `goodtill.subdomain`, `.username`, `.password`, `.base_url` |
| `GOOD_TILL_DOAMIN` | `GOOD_TILL_SUBDOMAIN` (the old name is still read as a fallback) |
| `goodtill:setup` | `goodtill:install`, `goodtill:status` |

<br><br>

## 🧪 Testing

```bash
composer test
```

<br><br>

## 🔒 Security

If you discover a security issue, please email ijeffrouk@gmail.com instead of using the issue tracker.

<br><br>

## 🙌 Credits

- [Phil Graham](https://github.com/ijeffro)
- [FLAIR](https://github.com/flairuk)
- [All Contributors](../../contributors)

<br><br>

## 📄 License

MIT. See [LICENSE](LICENSE.md).
