<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Brand extends API {

    /**
     * Create a new Good Till Customer instance.
     *
     * @param array $user
     * @return void
     * 
     * @source https://apidoc.thegoodtill.com/#api-Brand
     */
    public function __construct(array $user) {
        parent::__construct($user, $this->url);
    }

    /**
     * Set Outlet URL
     * 
     * @param string|null $url
     * @return void
     */
    public function setURL($url = null): void {
        $this->url = $url ? $url : Config::get('goodtill.routes.api') . 'outlets';
    }
    
    /**
     * Set Product Outlet ID
     * 
     * @param string $id
     * @return object
     */
    public function setID(string $id): object {
        $this->id = $id;
        return $this;
    }
}