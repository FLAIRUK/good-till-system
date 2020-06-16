<?php

namespace FLAIR\GoodTillSystem\Models;

use Illuminate\Support\Facades\Config;
use FLAIR\GoodTillSystem\API;

class Product extends API {

    /**
     * The url attribute.
     *
     * @var string
     */
    // protected $url = 'https://api.thegoodtill.com/api/products/';


    /**
     * The url attribute.
     *
     * @var string
     */
    // protected $url = 'https://api.thegoodtill.com/api/external_sale/products';


    /**
     * Create a new GoodTill instance.
     *
     * @return void
     */
    public function __construct($user, $id = null)
    {
        
        
        $this->id = $id;
        $this->url = Config::get('goodtill.routes.api') . 'products';
        $this->user = $user;


        parent::__construct($user, $this->url, $id = null);
    }


    public function url() {
        $this->url = Config::get('goodtill.routes.api') . 'products';
    }
}