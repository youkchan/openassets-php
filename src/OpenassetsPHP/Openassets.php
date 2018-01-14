<?php
namespace youkchan\OpenassetsPHP;
use youkchan\OpenassetsPHP\Api;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Provider;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use Exception;

class Openassets
{
    private $network;
    private $provider;

    public function __construct($params = array()){
        if (empty($params)) {
            $this->network = new Network();
            $this->provider = new Provider($this->network);
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
        $outputs = $this->get_unspent_outputs($mona_address_list);
        //$result = convert_to_hash($outputs);
        //return $result;
    }

    public function get_unspent_outputs($address_list = []) {
        Util::validate_addresses($address_list, $this->network->get_bclib_network());
        $unspent_list = $this->provider->list_unspent($address_list, $this->network);
        $output_result = array();
        foreach ($unspent_list as $item) {
            $output_result[] = self::get_output($item->txid,$item->vout);
        }
        return $output_result;
    }

    public function get_output($txid, $vout) {
        $decode_transaction = self::load_transaction($txid);
        $transaction = TransactionFactory::fromHex($decode_transaction);
        $colored_outputs = self::get_color_outputs_from_tx($transaction);
var_dump($transaction);
    }

    public function get_color_outputs_from_tx($transaction) {
    }
 
    public function load_transaction($txid) {
        $decode_transaction = $this->provider->get_transaction($txid, 0);
        if (empty($decode_transaction)) {
            throw new Exception("txid : " . $txid ." could not be retrieved");
        }
        return $decode_transaction;
    }
}
