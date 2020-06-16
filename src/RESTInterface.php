<?php

namespace FLAIR\GoodTillSystem;

interface RESTInterface {
    public function get();
    public function create(array $data);
    public function update(array $data);
    public function delete();
}
