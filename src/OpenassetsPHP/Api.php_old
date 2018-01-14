<?php
namespace youkchan\OpenassetsPHP;

class Api
{

    private $var = [
        "network" => "mainnet",
        "provider" => "monacoin",
        "cache" => "cache.db",
        "dust_limit" => 600,
        "default_fee" => 10000 ,
        "min_confirmation" => 1,
        "max_confirmation" => 9999999 ,
        "rpc_host" => "localhost" ,
        "rpc_port" =>  19402,
        "rpc_user" =>  "",
        "rpc_password" =>  "",
        "rpc_wallet" =>  "",
        "rpc_schema" =>  "https",
        "rpc_timeout" =>  60,
        "rpc_open_timeout" => 60,
    ];

    public function get($key,$default=null){
        if(array_key_exists($key,$this->var)){
            return $this->var[$key];
        }
        return $default;
    }

    public function set($key,$value){
        $this->var[$key] = $value;
    }

}
