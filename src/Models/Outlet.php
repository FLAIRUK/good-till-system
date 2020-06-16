<?php

namespace FLAIR\GoodTillSystem\Models;

use FLAIR\GoodTillSystem\API;

class Outlet extends API {

    /**
     * The url attribute.
     *
     * @var string
     */
    protected $url = 'https://api.thegoodtill.com/api/outlets/';

    protected $id;
    protected $outlet_name;
    protected $outlet_address;
    protected $outlet_city;

}