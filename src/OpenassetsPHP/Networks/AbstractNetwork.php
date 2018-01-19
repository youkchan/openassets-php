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
    protected $rpc_user;
    protected $rpc_password;
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

    public function get_max_confirmation() {
        return $this->max_confirmation;
    }

    public function get_min_confirmation() {
        return $this->min_confirmation;
    }

    public function get_timeout() {
        return $this->rpc_timeout;
    }

    public function get_dust_limit() {
        return $this->dust_limit;
    }

    public function get_default_fee() {
        return $this->default_fee;
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
