<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Staff extends API {

    /**
     * The url attribute.
     *
     * @var string
     */
    protected $url = 'staffs';

    const STAFF = 'staffs';

    /**
     * Create a new Good Till Product instance.
     *
     * @param array $user
     * @return void
     */
    public function __construct(array $user) {
        parent::__construct($user, $this->url);
    }

    public function setURL($url = null): void {
        $this->url = $url ? $url : Config::get('goodtill.routes.api') . self::STAFF;
    }

    
    /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setID($id): object {
        $this->id = $id;
        return $this;
    }

    /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setOutlet($id): object {
        $this->outlet_id = ['outlet_id' => $id];
        return $this;
    }

   /**
     * Product Set Outlet ID
     * 
     * @param $id
     * @return object
     */
    public function setName($name): object {
        $this->product_name = ['product_name' => $name];
        // $this->product_name = array_merge($this->payload, ['product_name' => $name ]);
        return $this;
    }

}