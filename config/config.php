<?php

/*
|---------------------------------------------------------------------------------
|
| The Good Till System || A Laravel Package to Bridge the API.
|
|---------------------------------------------------------------------------------
*/

return [

    /*
    |-------------------------------------------------------------------------
    | The Good Till System Creadencials
    |-------------------------------------------------------------------------
    |
    | Specify Client Creadencials for The Good Till System
    | @source https://apidoc.thegoodtill.com/#api-Authentication
    |
    | Subdomain Example: customertest
    | Username Example: customertest
    | Password Example: customertest
    |
    */

    'authorize' => [
        'subdomain' => env('GOOD_TILL_DOAMIN'),
        'username' => env('GOOD_TILL_USERNAME'),
        'password' => env('GOOD_TILL_PASSWORD'),
    ],
    'routes' => [
        'api' => 'https://api.thegoodtill.com/api/',
        'loyalty_api' => 'https://loyaltyapi.thegoodtill.com/api/',
        'web_order_api' => 'https://weborderapi.thegoodtill.com/api/'
    ]
];