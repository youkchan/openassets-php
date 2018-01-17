<?php
namespace youkchan\OpenassetsPHP\Networks;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use Exception;


class LitecoinTestnet
{
    private $default_network_data = array(
        "cache" => "cache.db",
        "dust_limit" => 600,
        "default_fee" => 10000 ,
        "min_confirmation" => 1,
        "max_confirmation" => 9999999 ,
        "rpc_host" => "localhost" ,
        "rpc_port" =>  19332,
        "rpc_user" =>  "rpc",
        "rpc_password" =>  "rpc",
        "rpc_wallet" =>  "",
        "rpc_schema" =>  "http",
        "rpc_timeout" =>  60,
        //"rpc_open_timeout" => 60,
    );

    private $network_data = array();

    public function __construct() {
        $this->network_data = $this->default_network_data;
    }

    public function get_max_confirmation() {
        return $this->network_data["max_confirmation"];
    }

    public function get_min_confirmation() {
        return $this->network_data["min_confirmation"];
    }

    public function get_timeout() {
        return $this->network_data["rpc_timeout"];
    }

    public function get_dust_limit() {
        return $this->network_data["dust_limit"];
    }

    public function get_server_url() {
        $url = $this->network_data["rpc_schema"] . "://";
        $url .= $this->network_data["rpc_user"] . ":" . $this->network_data["rpc_password"] . "@";
        $url .= $this->network_data["rpc_host"] . ":" . $this->network_data["rpc_port"];
        if ($this->network_data["rpc_wallet"] != "" ) {
            $url .= "/wallet" .  $this->network_data["rpc_wallet"];
        }
        return $url;
    }

}
