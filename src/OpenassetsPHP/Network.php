<?php
namespace youkchan\OpenassetsPHP;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use youkchan\OpenassetsPHP\Networks\MonacoinTestnet;
use Exception;


class Network
{

    private $bclib_network; //Bitcoin Library

    private $default_network_name = "monacoinTestnet";

    private $network;

    private $network_list = array(
        "bitcoin",
        "bitcoinTestnet",
        "monacoin",
        "monacoinTestnet",
        "litecoin",
        "litecoinTestnet"
    );

    public function __construct($params = array()){

        $default_network_name = $this->default_network_name;
        if (!empty($params) && array_key_exists("network", $params) ) {
            if ( in_array($params["network"], $this->network_list)) {
                $default_network_name = $params["network"];
            }
            unset($params["network"]);
        }
        Bitcoin::setNetwork(NetworkFactory::$default_network_name());
        $this->bclib_network = Bitcoin::getNetwork();

        $default_network_class_name = __NAMESPACE__ . "\\Networks\\" . ucfirst($this->default_network_name);
        $this->network = new $default_network_class_name;

        if (!empty($params)) {
            foreach ($params as $key => $value ){
                $this->network->set($key, $value);
            }
        }

    }

    public function set($var , $value) {
        $this->network->set($var, $value);
    }

    public function get($var) {
        return $this->network->get($var);
    }

    public function get_bclib_network() {
        return $this->bclib_network;
    }

    public function get_server_url() {
        return $this->network->get_server_url();
    }

    public function change_network($network) {
        if (!in_array($network, $this->network_list)) {
            throw new Exception($network . " is not supported" );
        }
        Bitcoin::setNetwork(NetworkFactory::$network());
        $this->bclib_network = Bitcoin::getNetwork();
    }

    public function get_p2pkh_address_prefix() {
        return $this->bclib_network->getAddressByte();
    }

}
