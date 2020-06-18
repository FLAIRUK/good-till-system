<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Customer extends API {

    /**
     * The url attribute.
     *
     * @var
     */
    protected $id;

    /**
     * Create a new Good Till Customer instance.
     *
     * @param array $user
     * @return void
     * 
     * @source https://apidoc.thegoodtill.com/#api-Outlet
     */
    public function __construct(array $user) {
        parent::__construct($user, $this->url);
    }

    /**
     * Set Outlet URL
     * 
     * @param string|null $id
     * @return void
     */
    public function setURL($id = null): void { 

        if (!is_null($id)) {
            $this->url = Config::get('goodtill.routes.api') . 'customers/' . $this->id ?? $this->id;
        } else {
            $this->url = $url;
        }
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
