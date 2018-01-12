<?php
namespace youkchan\OpenassetsPHP;
use youkchan\OpenassetsPHP\Api;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Provider;
use Exception;

class Openassets
{
//    private $api;
    private $network;
    private $default_network = "Monacoin";

    public function __construct($params = array()){
        if (empty($params)) {
            $this->network = new Network();
        }
//        $this->api = new Api();
//        Bitcoin::setNetwork(NetworkFactory::bitcoinTestnet());
//        $this->network = Bitcoin::getNetwork();
    }
/*
    public function set($key,$value){
        $this->api->set($key,$value);
    }

    public function get($key){
        return $this->api->get($key);
    }

    public function getApi(){
        return $this->api;
    }
*/
/*
    public function change_network($network) {
        $this->network->change_network($network);
       // Bitcoin::setNetwork(NetworkFactory::$network());
       // $this->network = Bitcoin::getNetwork();
    }
*/
    public function get_network() {
        return $this->network;
    }

    public function list_unspent($oa_address_list = []) {
        $mona_address_list = array();
        foreach ($oa_address_list as $oa_address) {
            $mona_address_list[] = Util::convert_oa_address_to_address($oa_address);
        }
        return $mona_address_list;
        //$outputs = get_unspent_outputs($mona_address_list);
        //$result = convert_to_hash($outputs);
        //return $result;
    }

    public function get_unspent_outputs($address_list = []) {
        Util::validate_addresses($address_list, $this->network->get_bclib_network());
        $unspent_list = Provider::list_unspent($address_list, $this->network);
    }
}
