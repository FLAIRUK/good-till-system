<?php

namespace FLAIRUK\GoodTillSystem;

use FLAIRUK\GoodTillSystem\Interfaces\GetInterface;
use FLAIRUK\GoodTillSystem\Interfaces\CreateInterface;
use FLAIRUK\GoodTillSystem\Interfaces\UpdateInterface;
use FLAIRUK\GoodTillSystem\Interfaces\DeleteInterface;


interface RESTInterface extends GetInterface, CreateInterface, UpdateInterface, DeleteInterface {
    
}
