<?php

namespace FLAIRUK\GoodTillSystem;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use FLAIRUK\GoodTillSystem\RESTInterface;

use FLAIRUK\GoodTillSystem\Models\Sale;
use FLAIRUK\GoodTillSystem\Models\Staff;
use FLAIRUK\GoodTillSystem\Models\Brand;
use FLAIRUK\GoodTillSystem\Models\Report;
use FLAIRUK\GoodTillSystem\Models\Outlet;
use FLAIRUK\GoodTillSystem\Models\VATRate;
use FLAIRUK\GoodTillSystem\Models\Voucher;
use FLAIRUK\GoodTillSystem\Models\Product;
use FLAIRUK\GoodTillSystem\Models\Loyalty;
use FLAIRUK\GoodTillSystem\Models\Customer;
use FLAIRUK\GoodTillSystem\Models\Register;
use FLAIRUK\GoodTillSystem\Models\Category;
use FLAIRUK\GoodTillSystem\Models\StaffClock;
use FLAIRUK\GoodTillSystem\Models\PaymentType;
use FLAIRUK\GoodTillSystem\Models\ExternalSale;

class GoodTillSystem extends Authorize {
        
    /**
     * Good Till: Connect
     * 
     * @param string $subdomain
     * @param string $username
     * @param string $password
     * @return void
     */
    public static function connect(
        string $subdomain, string $username, string $password
    ): void {
        // self::authorize()); WIPO
    }

    /**
     * Good Till: Prepare
     * 
     * @param RESTInterface $model
     * @return RESTInterface
     */
    public static function prepare(RESTInterface $model, string $id = null): RESTInterface {
        if ($id) $model->setID($id);
        
        $model->setURL($id);
        return $model;
    }

    /**
     * Good Till: API
     * 
     * @param RESTInterface $model
     * @return RESTInterface
     */
    public static function api(RESTInterface $model): RESTInterface {
        return $model;
    }

    /**
     * Good Till API: Customer
     * 
     * @param string $id
     * @return Customer
     * 
     * @source https://apidoc.thegoodtill.com/#api-Customer
     */
    public static function customer(string $id): Customer {
        return self::customers($id);
    }

    /**
     * Good Till API: Customers
     * 
     * @param null|string $id
     * @return Customer
     * 
     * @source https://apidoc.thegoodtill.com/#api-Customer
     */
    public static function customers(string $id = null): Customer {
        return self::api(self::prepare(new Customer(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Customer Group
     * 
     * @param null|string $id
     * @return CustomerGroup
     * 
     * @source https://apidoc.thegoodtill.com/#api-CustomerGroup
     */
    public static function customerGroup(string $id): CustomerGroup {
        return self::customerGroups($id);
    }

    /**
     * Good Till API: Customer Group
     * 
     * @param null|string $id
     * @return CustomerGroup
     * 
     * @source https://apidoc.thegoodtill.com/#api-CustomerGroup
     */
    public static function customerGroups(string $id = null): CustomerGroup {
        return self::api(self::prepare(new CustomerGroup(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Product
     * 
     * @param string $id
     * @return Product
     * 
     * @source https://apidoc.thegoodtill.com/#api-Product
     */  
    public static function product(string $id): Product {
        return self::products($id);
    }

    /**
     * Good Till API: Products
     * 
     * @param null|string $id
     * @return Product
     * 
     * @source https://apidoc.thegoodtill.com/#api-Product
     */
    public static function products(string $id = null): Product {
        return self::api(self::prepare(new Product(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Brand
     * 
     * @param string $id
     * @return Brand
     * 
     * @source https://apidoc.thegoodtill.com/#api-Brand
     */
    public static function brand(string $id): Brand {
        return self::brands($id);
    }

    /**
    * Create a new GoodTill instance.
     * @param null|string $id
     * @return Brand
     * 
     * @source https://apidoc.thegoodtill.com/#api-Brand
     */
    public static function brands(string $id = null): Brand {
        return self::api(self::prepare(new Brand(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Category
     * 
     * @param string $id
     * @return Category
     * 
     * @source https://apidoc.thegoodtill.com/#api-Category
     */
    public static function category(string $id): Category {
        return self::categories($id);
    }

    /**
     * Good Till API: Categories
     * 
     * @param null|string $id
     * @return Category
     * 
     * @source https://apidoc.thegoodtill.com/#api-Category
     */
    public static function categories(string $id = null): Category {
        return self::api(self::prepare(new Category(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Tag
     * 
     * @param string $id
     * @return Tag
     * 
     * @source https://apidoc.thegoodtill.com/#api-Tag
     */
    public static function tag(string $id): Tag {
        return self::tags($id);
    }

    /**
     * Good Till API: Tags
     * 
     * @param null|string $id
     * @return Tag
     * 
     * @source https://apidoc.thegoodtill.com/#api-Tag
     */
    public static function tags(string $id = null): Tag {
        return self::api(self::prepare(new Tag(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Outlets
     * 
     * @param string $id
     * @return Supplier
     * 
     * @source https://apidoc.thegoodtill.com/#api-Supplier
     */
    public static function supplier(string $id): Supplier {
        return self::suppliers($id);
    }

    /**
     * Good Till API: Suppliers
     * 
     * @param null|string $id
     * @return Supplier
     * 
     * @source https://apidoc.thegoodtill.com/#api-Supplier
     */
    public static function suppliers(string $id = null): Supplier {
        return self::api(self::prepare(new Supplier(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Ingredient
     * 
     * @param string $id
     * @return Ingredient
     * 
     * @source https://apidoc.thegoodtill.com/#api-Ingredient
     */
    public static function ingredient(string $id): Ingredient {
        return self::ingredients($id);
    }

    /**
     * Good Till API: Ingredients
     * 
     * @param null|string $id
     * @return Ingredient
     * 
     * @source https://apidoc.thegoodtill.com/#api-Ingredient
     */
    public static function ingredients(string $id = null): Ingredient {
        return self::api(self::prepare(new Ingredient(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Selling Layout
     * 
     * @param string $id
     * @return SellingLayout
     * 
     * @source https://apidoc.thegoodtill.com/#api-SellingLayout
     */
    public static function sellingLayout(string $id): SellingLayout {
        return self::sellingLayouts($id);
    }

    /**
     * Good Till API: Selling Layout
     * 
     * @param null|string $id
     * @return SellingLayout
     * 
     * @source https://apidoc.thegoodtill.com/#api-SellingLayout
     */
    public static function sellingLayouts(string $id = null): SellingLayout {
        return self::api(self::prepare(new SellingLayout(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Ecommerce
     * 
     * @param null|string $id
     * @return Ecommerce
     * 
     * @source https://apidoc.thegoodtill.com/#api-Ecommerce
     */
    public static function ecommerce(string $id = null): Ecommerce {
        return self::api(self::prepare(new Ecommerce(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Staff
     * 
     * @param null|string $id
     * @return Staff
     * 
     * @source https://apidoc.thegoodtill.com/#api-Staff
     */
    public static function staff(string $id = null): Staff {
        return self::api(self::prepare(new Staff(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Outlets
     * 
     * @param null|string $id
     * @return Outlet
     * 
     * @source https://apidoc.thegoodtill.com/#api-Tag
     */
    public static function staffClock(string $id = null) {
        return new OutletService;
    }

    /**
     * Good Till API: Sale
     * 
     * @param string $id
     * @return Sale
     * 
     * @source https://apidoc.thegoodtill.com/#api-Tag
     */
    public static function sale(string $id): Sales {
        return self::sales($id);
    }

    /**
     * Good Till API: Sales
     * 
     * @param null|string $id
     * @return Sale
     * 
     * @source https://apidoc.thegoodtill.com/#api-Tag
     */
    public static function sales(string $id = null): Sale {
        return self::api(self::prepare(new Sale(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: External Sale
     * 
     * @param null|string $id
     * @return ExternalSale
     * 
     * @source https://apidoc.thegoodtill.com/#api-ExternalSale
     */
    public static function externalSale(string $id = null): ExternalSale {
        return self::api(self::prepare(new ExternalSale(self::authorize()), $id ?? $id));
    }
    
    /**
     * Good Till API: External Sale
     * 
     * @param null|string $id
     * @return ExternalSale
     * 
     * @source https://apidoc.thegoodtill.com/#api-ExternalSale
     */
    public static function external(): ExternalSale {
        return self::externalSale();
    }

    /**
     * Good Till API: VAT Rate
     * 
     * @param string $id
     * @return VATRate
     * 
     * @source https://apidoc.thegoodtill.com/#api-VatRate
     */
    public static function vatRate(string $id): VATRate {
        return self::vatRates($id);
    }

    /**
     * Good Till API: VAT Rates
     * 
     * @param string|null $id
     * @return VATRate
     * 
     * @source https://apidoc.thegoodtill.com/#api-VatRate
     */
    public static function vatRates(string $id = null): VATRate {
        return self::api(self::prepare(new VATRate(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Payment Type
     * 
     * @param string $id
     * @return PaymentType
     * 
     * @source https://apidoc.thegoodtill.com/#api-PaymentType
     */
    public static function paymentType(string $id): PaymentType {
        return self::paymentTypes($id);
    }

    /**
     * Good Till API: Payment Types
     * 
     * @param null|string $id
     * @return PaymentType
     * 
     * @source https://apidoc.thegoodtill.com/#api-PaymentType
     */
    public static function paymentTypes(string $id = null): PaymentType {
        return self::api(self::prepare(new PaymentType(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Register
     * 
     * @return Register
     * 
     * @source Undocument Feature
     * @source https://apidoc.thegoodtill.com/#api-Register
     */
    public static function register(string $id): Register {
        return self::registers($id);
    }

    /**
     * Good Till API: Registers
     * 
     * @param string|null $id
     * @return Register
     * 
     * @source https://apidoc.thegoodtill.com/#api-Register
     */
    public static function registers(string $id = null): Register {
        return self::api(self::prepare(new Register(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Vouchers
     * 
     * @return Voucher
     * 
     * @source Good Till Pro Feature
     * @source https://apidoc.thegoodtill.com/#api-Voucher
     */
    public static function vouchers(string $id = null): Voucher {
        return self::api(self::prepare(new Voucher(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Loyalty
     * 
     * @param string $id
     * @return Loyalty
     * 
     * @source https://apidoc.thegoodtill.com/#api-Loyalty
     */
    public static function loyalty(string $id): Loyalty {
        return self::loyalties($id);
    }

    /**
     * Good Till API: Loyalties
     * 
     * @return Loyalty
     * 
     * @source https://apidoc.thegoodtill.com/#api-Loyalty
     */
    public static function loyalties(string $id = null): Loyalty {
        return self::api(self::prepare(new Loyalty(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Report
     * 
     * @param string $id
     * @return Report
     * 
     * @source https://apidoc.thegoodtill.com/#api-Report
     */
    public static function report(string $id): Report {
        return self::reports($id);
    }

    /**
     * Good Till API: Reports
     * 
     * @return Report
     * 
     * @source https://apidoc.thegoodtill.com/#api-Report
     */
    public static function reports(string $id = null): Report {
        return self::api(self::prepare(new Report(self::authorize()), $id ?? $id));
    }

    /**
     * Good Till API: Outlet
     * 
     * @param string $id
     * @return Outlet
     * 
     * @source https://apidoc.thegoodtill.com/#api-Outlet
     */
    public static function outlet(string $id): Outlet {
        return self::outlets($id);
    }

    /**
     * Good Till API: Outlets
     * 
     * @param null|string $id
     * @return Outlet
     * 
     * @source https://apidoc.thegoodtill.com/#api-Outlet
     */
    public static function outlets(string $id = null): Outlet {
        return self::api(self::prepare(new Outlet(self::authorize()), $id ?? $id));
    }
}
