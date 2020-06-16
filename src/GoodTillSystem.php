<?php

namespace FLAIR\GoodTillSystem;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use FLAIR\GoodTillSystem\RESTInterface;

use FLAIR\GoodTillSystem\Models\Sale;
use FLAIR\GoodTillSystem\Models\Staff;
use FLAIR\GoodTillSystem\Models\Brand;
use FLAIR\GoodTillSystem\Models\Outlet;
use FLAIR\GoodTillSystem\Models\Product;
use FLAIR\GoodTillSystem\Models\Customer;
use FLAIR\GoodTillSystem\Models\Category;

class GoodTillSystem {

    const SUBDOMAIN = 'subdomain';
    const USERNAME = 'username';
    const PASSWORD = 'password';

    public static function authorize() {
        return Http::post(Config::get('goodtill.routes.api') . 'login', [
            self::SUBDOMAIN => Config::get('goodtill.authorize.subdomain'),
            self::USERNAME => Config::get('goodtill.authorize.username'),
            self::PASSWORD => Config::get('goodtill.authorize.password')
        ])->json();
    }

    /**
     * Good Till System: API
     * 
     * @param RESTInterface $model
     * @return RESTInterface
     */
    public static function api(RESTInterface $model): RESTInterface {
        return $model;
    }

    /**
     * Good Till System API: Outlet
     * 
     * @param string $id
     * @return Outlet
     */
    public static function outlet(string $id): Outlet {
        return self::outlets($id);
    }

    /**
     * Good Till System API: Outlets
     * 
     * @param null|string $id
     * @return Outlet
     */
    public static function outlets(string $id = null): Outlet {
        return self::api(new Outlet(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Customer
     * 
     * @param null|string $id
     * @return Customer
     */
    public static function customer(string $id): Customer {
        return self::customers($id);
    }

    /**
     * Good Till System API: Customers
     * 
     * @param null|string $id
     * @return Customer
     */
    public static function customers(string $id = null): Customer {
        return self::api(new Customer(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Customer Group
     * 
     * @param null|string $id
     * @return CustomerGroup
     */
    public static function customerGroup(string $id = null): CustomerGroup {
        return self::api(new CustomerGroup(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Product
     * 
     * @param string $id
     * @return Product
     */  
    public static function product(string $id): Product {
        return self::products($id);
    }

    /**
     * Good Till System API: Products
     * 
     * @param null|string $id
     * @return Product
     */
    public static function products(string $id = null): Product {
        return self::api(new Product(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Brand
     * 
     * @param string $id
     * @return Brand
     */
    public static function brand(string $id): Brand {
        return self::brands($id);
    }

    /**
     * Good Till System API: Brands
     * 
     * @param null|string $id
     * @return Brand
     */
    public static function brands(string $id = null): Brand {
        return self::api(new Brand(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Category
     * 
     * @param string $id
     * @return Category
     */
    public static function category(string $id): Category {
        return self::categories($id);
    }

    /**
     * Good Till System API: Outlets
     * 
     * @param null|string $id
     * @return Category
     */
    public static function categories(string $id = null): Category {
        return self::api(new Category(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Tag
     * 
     * @param string $id
     * @return Tag
     */
    public static function tag(string $id): Tag {
        return self::tags($id);
    }

    /**
     * Good Till System API: Tags
     * 
     * @param null|string $id
     * @return Tag
     */
    public static function tags(string $id = null): Tag {
        return self::api(new Tag(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Outlets
     * 
     * @param string $id
     * @return Supplier
     */
    public static function supplier(string $id): Supplier {
        return self::suppliers($id);
    }

    /**
     * Good Till System API: Suppliers
     * 
     * @param null|string $id
     * @return Supplier
     */
    public static function suppliers(string $id = null): Supplier {
        return self::api(new Supplier(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Ingredient
     * 
     * @param string $id
     * @return Ingredient
     */
    public static function ingredient(string $id): Ingredient {
        return self::ingredients($id);
    }

    /**
     * Good Till System API: Ingredients
     * 
     * @param null|string $id
     * @return Ingredient
     */
    public static function ingredients(string $id = null): Ingredient {
        return self::api(new Ingredient(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: SellingLayout
     * 
     * @param null|string $id
     * @return SellingLayout
     */
    public static function sellingLayout(string $id = null): SellingLayout {
        return self::api(new SellingLayout(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Ecommerce
     * 
     * @param null|string $id
     * @return Ecommerce
     */
    public static function ecommerce(string $id = null): Ecommerce {
        return self::api(new Ecommerce(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Staff
     * 
     * @param null|string $id
     * @return Staff
     */
    public static function staff(string $id = null): Staff {
        return self::api(new Staff(self::authorize(), $id ?? $id));
    }

    /**
     * Good Till System API: Outlets
     * 
     * @param null|string $id
     * @return Outlet
     */
    public static function staffClock(string $id = null) {
        return new OutletService;
    }

    /**
     * Good Till System API: Sale
     * 
     * @param string $id
     * @return Sale
     */
    public static function sale(string $id): Sales {
        return self::sales($id);
    }

    /**
     * Good Till System API: Sales
     * 
     * @param null|string $id
     * @return Sale
     */
    public static function sales(string $id = null): Sale {
        return self::api(new Sale(self::authorize(), $id ?? $id));
    }
}
