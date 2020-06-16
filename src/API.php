<?php

namespace FLAIR\GoodTillSystem;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use FLAIR\GoodTillSystem\RESTInterface;
use Illuminate\Http\Client\PendingRequest;

class API implements RESTInterface {

    protected $url;

    /**
     * Create a new GoodTill instance.
     *
     * @return void
     */
    public function __construct($user, $url, $id = null)
    {

        $this->id = $id;
        $this->url = $url;
        $this->user = $user;
    }

    /**
     * Good Till API: Access
     * 
     * @return PendingRequest
     */
    public function access(): PendingRequest {
        return Http::withToken($this->user['token']);
    }

    /**
     * Good Till API: Get Outlet Model
     * 
     * @return array
     */
    public function get(): array {
       
        return $this->access()->get($this->url . $this->id ?? $this->id)->json();
    }

    /**
     * Good Till API: Create
     * 
     * @param array $data
     * @return array
     */
    public function create(array $data): array {
        return $this->access()->post($this->url, $data)->json();
    }

    /**
     * Good Till API: Update
     * 
     * @param array $data
     * @return array
     */
    public function update(array $data): array {
        return $this->access()->patch($this->url . $this->id ?? $this->id, $data)->json();
    }

    /**
     * Good Till API: Delete
     * 
     * @param array $data
     * @return array
     */
    public function delete(): array {
        return $this->access()->post($this->url . 'delete/' . $this->id, $data)->json();
    }
}
