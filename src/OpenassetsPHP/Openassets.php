<?php
namespace youkchan\OpenassetsPHP;
use youkchan\OpenassetsPHP\Api;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Provider;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Script\Script;
//use BitWasp\Bitcoin\Crypto\Hash;
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
    }

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
//var_dump($txid);
        $decode_transaction = self::load_transaction($txid);
        $transaction = TransactionFactory::fromHex($decode_transaction);
//var_dump($transaction->getTxId());
        $colored_outputs = self::get_color_outputs_from_tx($transaction);
        //return $colored_outputs[$vout]; //TODO?
        return $colored_outputs;
    }

    public function get_color_outputs_from_tx($transaction) {
/*        if(!$transaction->isCoinbase()) {
            foreach ($transaction->getOutputs() as $output_key => $output) {
        //          var_dump($output->getScript()->getBuffer());
                  //var_dump(Buffer::hex($output->hex));
                $marker_output_payload = MarkerOutput::parse_script($output->getScript()->getBuffer());
                if (!is_null($marker_output_payload)) {
                    $marker_output = MarkerOutput::deserialize_payload($marker_output_payload);
                    $previous_outputs = array();

                    foreach ($transaction->getInputs() as $previous_input) {
//var_dump($previous_input);
                        $previous_outputs[] = self::get_output($previous_input->getOutpoint()->getTxId()->getHex(),$previous_input->getOutpoint()->getVout());
                    }
//var_dump($previous_outputs);
       //            $asset_ids = self::compute_asset_ids($previous_outputs, $output_key, $transaction, $marker_output->get_asset_quantities());
        //            if (!is_null($assets_ids)) {
        //                return $assets_ids;
        //            }
//var_dump($previous_outputs);
                }
                //var_dump($output->getScript());
            }
        }
*/ 
        $colored_outputs = array();
        foreach ($transaction->getOutputs() as $output) {
            $colored_outputs[] = new OaTransactionOutput($output->getValue(), $output->getScript(), null, 0 ,OutputType::UNCOLORED);
        }
        return $colored_outputs;
    }

    public function compute_asset_ids ($previous_outputs, $marker_output_index, $transaction, $asset_quantities) {
        $outputs = $transaction->getOutputs();
        if ($asset_quantities > count($outputs) - 1 || count($previous_outputs) == 0) {
            return null;
        }
        $result = array();
        $marker_output = outputs[$marker_output_index];
//script_to_asset_id($previous_outputs[0]

    }
 
    public function load_transaction($txid) {
        $decode_transaction = $this->provider->get_transaction($txid, 0);
        if (empty($decode_transaction)) {
            throw new Exception("txid : " . $txid ." could not be retrieved");
        }
        return $decode_transaction;
    }
}
