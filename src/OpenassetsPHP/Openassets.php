<?php
namespace youkchan\OpenassetsPHP;
use youkchan\OpenassetsPHP\Api;

class Openassets
{
    private $api;

    public function __construct(){
        $this->api = new Api();
    }

    public function set($key,$value){
        $this->api->set($key,$value);
    }

    public function get($key){
        return $this->api->get($key);
    }

    public function getApi(){
        return $this->api;
    }
}
