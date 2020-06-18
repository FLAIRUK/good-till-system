<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class VATRate extends API {

    /**
     * Create a new Good Till VATRate instance.
     *
     * @param array $user
     * @return void
     * 
     * @source https://apidoc.thegoodtill.com/#api-VatRate
     */
    public function __construct(array $user) {
        parent::__construct($user, $this->url);
    }

    /**
     * Set VATRate URL
     * 
     * @param string|null $url
     * @return void
     */
    public function setURL($url = null): void {
        $this->url = $url ? $url : Config::get('goodtill.routes.api') . 'ajax/vat_rates';
    }
    
    /**
     * Set VATRate Outlet ID
     * 
     * @param string $id
     * @return object
     */
    public function setID(string $id): object {
        $this->id = $id;
        return $this;
    }
}