<?php
namespace youkchan\OpenassetsPHP;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use youkchan\OpenassetsPHP\Networks\MonacoinTestnet;
use Exception;


class Network
{

    private $bclib_network; //Bitcoin Library

    private $default_network_name = "litecoinTestnet";

    private $network;

    private $network_list = array(
        "bitcoin",
        "bitcoinTestnet",
        "monacoin",
        "monacoinTestnet",
        "litecoin",
        "litecoinTestnet"
    );

    public function __construct(){
        $default_network_name = $this->default_network_name;
        Bitcoin::setNetwork(NetworkFactory::$default_network_name());
        $this->bclib_network = Bitcoin::getNetwork();

        $default_network_class_name = __NAMESPACE__ . "\\Networks\\" . ucfirst($this->default_network_name);
        $this->network = new $default_network_class_name;
    }

    public function get_bclib_network() {
        return $this->bclib_network;
    }

    public function get_min_confirmation() {
        return $this->network->get_min_confirmation();
    }

    public function get_max_confirmation() {
        return $this->network->get_max_confirmation();
    }

    public function get_timeout() {
        return $this->network->get_timeout();
    }

    public function get_server_url() {
        return $this->network->get_server_url();
    }

    public function get_dust_limit() {
        return $this->network->get_dust_limit();
    }

    public function get_default_fee() {
        return $this->network->get_default_fee();
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
