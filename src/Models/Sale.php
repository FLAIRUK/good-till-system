<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Sale extends API {

    protected $url;

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

    public function setURL($url = null): void {
        $this->url = $url ? $url : Config::get('goodtill.routes.api') . 'sales/';
    }


      /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function summary($name): object {
        $this->product_name = ['product_name' => $name];
        // $this->product_name = array_merge($this->payload, ['product_name' => $name ]);
        return $this;
    }
    
}
