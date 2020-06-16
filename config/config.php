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
    | Docs: https://apidoc.thegoodtill.com/#api-Authentication
    |
    | Subdomain Example: customertest
    | Key Example: customertest
    | Secret Example: customertest
    |
    */

    'subdomain' => env('GOOD_TILL_DOAMIN'),
    'username' => env('GOOD_TILL_USERNAME'),
    'password' => env('GOOD_TILL_PASSWORD')
];