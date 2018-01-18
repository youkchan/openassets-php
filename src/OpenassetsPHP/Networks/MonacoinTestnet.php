<?php
namespace youkchan\OpenassetsPHP\Networks;
use youkchan\OpenassetsPHP\Networks\AbstractNetwork;
use Exception;


class MonacoinTestnet extends AbstractNetwork
{
/*    private $default_network_data = array(
        "cache" => "cache.db",
        "dust_limit" => 600,
        "default_fee" => 10000 ,
        "min_confirmation" => 1,
        "max_confirmation" => 9999999 ,
        "rpc_host" => "localhost" ,
        "rpc_port" =>  19402,
        "rpc_user" =>  "rpc",
        "rpc_password" =>  "rpc",
        "rpc_wallet" =>  "",
        "rpc_schema" =>  "http",
        "rpc_timeout" =>  60,
        //"rpc_open_timeout" => 60,
    );

    private $network_data = array();
*/
    public function __construct() {
        $this->cache = "cache.db";
        $this->dust_limit = 600;
        $this->default_fee = 10000;
        $this->min_confirmation = 1; 
        $this->max_confirmation = 9999999; 
//        $this->rpc_host = "localhost";
        $this->rpc_port = 19402;
        $this->rpc_user = "rpc";
        $this->rpc_password = "rpc";
        $this->rpc_wallet = "";
//        $this->rpc_schema = "http";
        $this->rpc_timeout = 60;
    }


}
