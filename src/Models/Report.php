<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;
use FLAIRUK\GoodTillSystem\Models\Sale;
use FLAIRUK\GoodTillSystem\Models\Product;

class Report extends API {


    protected $url;

    const SALE = 'sale';
    const PRODUCTS = 'products';
    const REPORT = 'report';

    /**
     * Create a new GoodTill instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;

        parent::__construct($user, $this->url);
    }

    public function setURL(): void {
        $this->url = Config::get('goodtill.routes.api') . self::REPORT . '/';
    }

    /**
     * External Products
     * 
     * @return Product
     */
    public function products(): Product {
        $sale = new Product($this->user);
        $sale->setURL($this->url . self::PRODUCTS . '/');
        return $sale;
    }

    public function sale($id = null): Sale {
        $sale = new Sale($this->user);
        $sale->setURL($this->url . self::SALE . '/' . $id ?? $id);
        return $sale;

    }

    public function status() {
        $this->url = $this->url . self::SALE . '/';
        return $this;
    }  
}
