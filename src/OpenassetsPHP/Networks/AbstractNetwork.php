<?php
namespace youkchan\OpenassetsPHP\Networks;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use Exception;


class AbstractNetwork
{

    protected $cache;
    protected $dust_limit;
    protected $default_fee;
    protected $min_confirmation = 1;
    protected $max_confirmation = 9999999;
    protected $rpc_host = "localhost";
    protected $rpc_port;
    protected $rpc_user = "rpc";
    protected $rpc_password = "rpc";
    protected $rpc_wallet;
    protected $rpc_schema = "http";
    protected $rpc_timeout = 60;


    public function set($var , $value) {
        if (property_exists($this, $var)) {
            $this->$var = $value;
        } else {
            throw new Exception(" cannot set property : " . $$var );
        }
    }

    public function get($var) {
        if (property_exists($this, $var)) {
            return $this->$var;
        } else {
            throw new Exception(" cannot get property : " . $$var );
        }
    }

    public function get_server_url() {
        $url = $this->rpc_schema . "://";
        $url .= $this->rpc_user . ":" . $this->rpc_password . "@";
        $url .= $this->rpc_host . ":" . $this->rpc_port;
        if ($this->rpc_wallet != "" ) {
            $url .= "/wallet" .  $this->rpc_wallet;
        }
        return $url;
    }

}
