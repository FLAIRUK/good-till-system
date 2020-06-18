<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class Loyalty extends API {

    protected $url;

    const LOYALTY = 'reward_rules';

    /**
     * Create a new GoodTill instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        parent::__construct($user, $this->url);
    }

    public function setURL(): void {
        $this->url = Config::get('goodtill.routes.api') . self::LOYALTY . '/';
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
