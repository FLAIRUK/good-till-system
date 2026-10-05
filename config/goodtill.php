<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Goodtill only allows store_owner / admin users to use the API. Create a
    | dedicated user for your integration so that "log out of all sessions"
    | in the back office doesn't invalidate the integration's token.
    |
    | @see https://apidoc.thegoodtill.com/#api-Authentication-CreateToken
    |
    */

    // GOOD_TILL_DOAMIN is the 0.x name. goodtill:install writes an empty
    // GOOD_TILL_SUBDOMAIN, so an empty value falls back to it too.
    'subdomain' => env('GOOD_TILL_SUBDOMAIN') ?: env('GOOD_TILL_DOAMIN'),

    'username' => env('GOOD_TILL_USERNAME'),

    'password' => env('GOOD_TILL_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Default outlet
    |--------------------------------------------------------------------------
    |
    | Sent as the Outlet-Id header on every request. Leave empty to use the
    | user's current outlet; override per call with GoodTill::forOutlet($id).
    |
    */

    'outlet_id' => env('GOOD_TILL_OUTLET_ID'),

    /*
    |--------------------------------------------------------------------------
    | HTTP
    |--------------------------------------------------------------------------
    */

    'base_url' => env('GOOD_TILL_BASE_URL', 'https://api.thegoodtill.com/api'),

    'timeout' => (int) env('GOOD_TILL_TIMEOUT', 30),

    // GET requests are retried on connection errors and 5xx responses: [times, sleep milliseconds].
    // Writes are never retried automatically, to avoid duplicate sales or records.
    'retry' => [2, 250],

    /*
    |--------------------------------------------------------------------------
    | Token cache
    |--------------------------------------------------------------------------
    |
    | Tokens last 12 hours and can be refreshed for two weeks, so the token is
    | cached and refreshed automatically. Use a shared store (redis, database)
    | when running several servers or queue workers.
    |
    */

    'cache_store' => env('GOOD_TILL_CACHE_STORE'),

    'refresh_after_minutes' => 11 * 60,

];
