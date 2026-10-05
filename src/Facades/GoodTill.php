<?php

namespace FLAIRUK\GoodTillSystem\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \FLAIRUK\GoodTillSystem\GoodTill forOutlet(?string $outletId)
 * @method static string|null outletId()
 * @method static \FLAIRUK\GoodTillSystem\TokenManager tokens()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Brands brands()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Categories categories()
 * @method static \FLAIRUK\GoodTillSystem\Resources\CustomerGroups customerGroups()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Customers customers()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Ecommerce ecommerce()
 * @method static \FLAIRUK\GoodTillSystem\Resources\ExternalSales externalSales()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Ingredients ingredients()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Outlets outlets()
 * @method static \FLAIRUK\GoodTillSystem\Resources\PaymentTypes paymentTypes()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Products products()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Promotions promotions()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Registers registers()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Reports reports()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Sales sales()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Staff staff()
 * @method static \FLAIRUK\GoodTillSystem\Resources\StaffClockRecords staffClockRecords()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Suppliers suppliers()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Tags tags()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Users users()
 * @method static \FLAIRUK\GoodTillSystem\Resources\VatRates vatRates()
 * @method static \FLAIRUK\GoodTillSystem\Resources\Vouchers vouchers()
 * @method static mixed config()
 * @method static mixed get(string $path, array $query = [])
 * @method static mixed post(string $path, array $data = [])
 * @method static mixed put(string $path, array $data = [])
 * @method static mixed patch(string $path, array $data = [])
 * @method static mixed delete(string $path, array $data = [])
 * @method static mixed send(string $method, string $path, array $options = [])
 * @method static \Illuminate\Http\Client\PendingRequest request()
 *
 * @see \FLAIRUK\GoodTillSystem\GoodTill
 */
class GoodTill extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \FLAIRUK\GoodTillSystem\GoodTill::class;
    }
}
