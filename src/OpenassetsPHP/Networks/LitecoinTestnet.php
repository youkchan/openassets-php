<?php
namespace youkchan\OpenassetsPHP\Networks;
use youkchan\OpenassetsPHP\Networks\AbstractNetwork;
use Exception;


class LitecoinTestnet extends AbstractNetwork

{

    public function __construct() {
        $this->cache = "cache.db";
        $this->dust_limit = 600;
        $this->default_fee = 10000;
        $this->min_confirmation = 1; 
        $this->max_confirmation = 9999999; 
        $this->rpc_port = 19332;
        $this->rpc_wallet = "";
        $this->rpc_timeout = 60;
    }

}
