<?php

namespace FLAIRUK\GoodTillSystem;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use FLAIRUK\GoodTillSystem\RESTInterface;

class Authorize extends API {

    /**
     * Good Till Authentication
     *
     * @source https://apidoc.thegoodtill.com/#api-Authentication
     */

    const LOGIN = 'login';
    const LOGOUT = 'logout';
    const REFRESH = 'refresh_token';

    const SUBDOMAIN = 'subdomain';
    const USERNAME = 'username';
    const PASSWORD = 'password';

    const CONFIG_ROUTE = 'goodtill.routes.api';
    const CONFIG_SUBDOMAIN = 'goodtill.authorize.subdomain';
    const CONFIG_USERNAME = 'goodtill.authorize.username';
    const CONFIG_PASSWORD = 'goodtill.authorize.password';

    /**
     * Good Till System: Authorize
     * 
     * @return array
     * @source https://apidoc.thegoodtill.com/#api-Authentication-CreateToken
     */
    public static function authorize(): array {
        return self::authorizer(Config::get(self::CONFIG_ROUTE) . self::LOGIN);
    }

    /**
     * Good Till System: Revoke
     * 
     * @return array
     * @source https://apidoc.thegoodtill.com/#api-Authentication-InvalidateToken
     */
    public static function revoke(): array {
        return self::authorizer(Config::get(self::CONFIG_ROUTE) . self::LOGOUT);
    }

    /**
     * Good Till System: Refresh
     * 
     * @return array
     * @source https://apidoc.thegoodtill.com/#api-Authentication-RefreshToken
     */
    public static function refresh(): array {
        return self::authorizer(Config::get(self::CONFIG_ROUTE) . self::REFRESH);
    }

    /**
     * Good Till System: Authorizer
     * 
     * @param string $route
     * @return array
     */
    public static function authorizer(string $route): array {
        return Http::post($route, [
            self::SUBDOMAIN => Config::get(self::CONFIG_SUBDOMAIN),
            self::USERNAME => Config::get(self::CONFIG_USERNAME),
            self::PASSWORD => Config::get(self::CONFIG_PASSWORD)
        ])->json();
    }
}