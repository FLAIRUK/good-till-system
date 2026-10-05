<?php

namespace FLAIRUK\GoodTillSystem\Resources;

use FLAIRUK\GoodTillSystem\Resources\Concerns\CreatesRecords;

/**
 * @see https://apidoc.thegoodtill.com/#api-Voucher
 */
class Vouchers extends Resource
{
    use CreatesRecords;

    protected string $path = 'vouchers';
}
