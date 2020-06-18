<?php

namespace FLAIRUK\GoodTillSystem\Models;

use FLAIRUK\GoodTillSystem\API;
use Illuminate\Support\Facades\Config;

class PaymentType extends API {

    protected $url;

    const PAYMENT_TYPES = 'ajax/payment_types';

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
        $this->url = Config::get('goodtill.routes.api') . self::REGISTERS . '/';
    }
}
