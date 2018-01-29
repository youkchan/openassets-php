<?php
namespace youkchan\OpenassetsPHP;
use youkchan\OpenassetsPHP\Api;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Provider;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use youkchan\OpenassetsPHP\Transaction\TransferParameters;
use youkchan\OpenassetsPHP\Transaction\TransactionBuilder;
use youkchan\OpenassetsPHP\Transaction\OaOutPoint;
use youkchan\OpenassetsPHP\Transaction\SpendableOutput;
use youkchan\OpenassetsPHP\Cache\TransactionCache;
use youkchan\OpenassetsPHP\Cache\OutputCache;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Script\Script;
use Exception;

class Openassets
{
    private $network;
    private $provider;
    private $transaction_cache = null;
    private $output_cache = null;

    public function __construct($params = array()){
        if (empty($params)) {
            $this->network = new Network();
        }else {
            $this->network = new Network($params);
        }
        $this->provider = new Provider($this->network);
        if (!is_null($this->network->get("cache"))) {
            $this->transaction_cache = new TransactionCache($this->network->get("cache"));
            $this->output_cache = new OutputCache($this->network->get("cache"));
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
        return $outputs;
    }

    public function get_balance($address = null) {

        if (is_null($address)) {
            $address_list = [];
        }else {
            $address_list = [Util::convert_oa_address_to_address($address)];
        }
        $outputs = $this->get_unspent_outputs($address_list);
        $output_group_by_address = [];
        foreach ($outputs as $output) {
            $coin_address = Util::script_to_address($output->output()->get_script(), $this->network->get_bclib_network());
            $is_address_in_array = false;
            $balance_group_by_address = array();
            $asset = null;
            foreach ($output_group_by_address as $key => $each_balance) {
                if (is_array($each_balance) && $coin_address === $key ) {
                    if (!is_null($output->output->asset_id)) {
                        $asset["asset_id"] = $output->output->asset_id;
                        $asset["quantity"] = $output->output->asset_quantity;
                        $asset["amount"] = $output->output->get_asset_amount();
                        $asset["asset_definition_url"] = $output->output->asset_definition_url;
                        $asset["proof_of_authenticity"] = $output->output->get_proof_of_authenticity();
                        $output_group_by_address[$key]["assets"][] = $asset;
                    }
                    $output_group_by_address[$key]["value"] = $each_balance["value"] + $output->output->value;
                    $is_address_in_array = true;
                    break;
                }
            }
            if (!$is_address_in_array) {
                $user["address"] = $coin_address;
                if (is_null($coin_address)) {
                    $user["oa_address"] = null;
                } else {
                    $user["oa_address"] = Util::convert_address_to_oa_address($coin_address);
                }
                $user["account"] = $output->output->account;
                $value = $output->output->value;
                if (!is_null($output->output->asset_id)) {
                    $asset["asset_id"] = $output->output->asset_id;
                    $asset["quantity"] = $output->output->asset_quantity;
                    $asset["amount"] = $output->output->get_asset_amount();
                    $asset["asset_definition_url"] = $output->output->asset_definition_url;
                    $asset["proof_of_authenticity"] = $output->output->get_proof_of_authenticity();
                    $output_group_by_address[$coin_address]["assets"][] = $asset;
                }
                $output_group_by_address[$coin_address]["user"] = $user;
                $output_group_by_address[$coin_address]["value"] = $value;
            }
        }

        foreach ($output_group_by_address as $key => $each_balance) {
            if(isset($each_balance["assets"]) && count($each_balance["assets"]) >= 2) {
                $asset_list = [];
                $is_asset_in_array = false;
                foreach ($each_balance["assets"] as $asset) {
                    foreach ($asset_list as $item) {
                        if (is_array($item) && $asset["asset_id"] === $item["asset_id"] ) {
                            $asset["quantity"] = $item["quantity"] + $asset["quantity"];
                            $asset["amount"] = $item["amount"] + $asset["amount"];
                            $asset_list[$asset["asset_id"]] = $asset;
                            $is_asset_in_array = true;
                            break;
                        }
                    }
                    if (!$is_asset_in_array) {
                        $asset_list[$asset["asset_id"]] = $asset;
                    }
                }

                $assets = [];
                foreach ($asset_list as $item){
                    $assets[] = $item;
                }
                $output_group_by_address[$key]["assets"] = $assets;
            
            }
        }
        $result = $output_group_by_address; 
        return $result;
    }

    public function get_unspent_outputs($address_list = []) {
        Util::validate_addresses($address_list, $this->network->get_bclib_network());
        $unspent_list = $this->provider->list_unspent($address_list, $this->network);
        $result = array();
        foreach ($unspent_list as $item) {
            $output_result = self::get_output($item->txid,$item->vout);
            $output_result->account = $item->account;
            $out_point = new OaOutPoint($item->txid, $item->vout);
            $output = new SpendableOutput($out_point, $output_result);
            $output->confirmations = $item->confirmations;
            $output->spendable = $item->spendable;
            $output->solvable = $item->solvable;
            $result[] = $output;
        }
        return $result;
    }

    public function issue_asset($from, $quantity, $metadata = null, $to = null, $fee = null, $mode = "broadcast", $output_quantity = 1) {
  
        if (is_null($from) || is_null($quantity)) {
            throw new Exception("the parameters from and quantity should not be null. ");
        }
 
        if (is_null($to)) {
            $to = $from;
        } 
        $colored_outputs = self::get_unspent_outputs([Util::convert_oa_address_to_address($from)]);
        $issue_param = new TransferParameters($colored_outputs, $to, $from, $quantity, $output_quantity, $this->network->get_bclib_network());
        $issue_param->validate_address("both");
        $transaction_builder = self::create_transaction_builder();
        $transaction = $transaction_builder->issue_asset($issue_param, $metadata, $fee);
        $transaction_id = self::process_transaction($transaction);
        return $transaction_id;
    }

    public function send_asset($from, $asset_id, $quantity, $to, $fee = null, $mode = "broadcast", $output_quantity = 1) {
        if (is_null($from) || is_null($quantity)) {
            throw new Exception("the parameters from , asset_id and quantity should not be null. ");
        }

        $colored_outputs = self::get_unspent_outputs([Util::convert_oa_address_to_address($from)]);
        $asset_transfer_spec = new TransferParameters($colored_outputs, $to, $from, $quantity, $output_quantity, $this->network->get_bclib_network());
        $transaction_builder = self::create_transaction_builder();
        $transaction = $transaction_builder->transfer_asset($asset_id, $asset_transfer_spec, $from, $fee);
        $transaction_id = self::process_transaction($transaction);
        return $transaction_id;
    }

    public function get_output($txid, $vout) {
        $cache = self::load_cached_output($txid, $vout);
        if ($cache) {
            return $cache;
        }
        $decode_transaction = self::load_cached_transaction($txid);
        $transaction = TransactionFactory::fromHex($decode_transaction);
        $colored_outputs = self::get_color_outputs_from_tx($transaction);
        self::set_cached_output($txid, $colored_outputs);
        return $colored_outputs[$vout]; 
    }

    public function get_color_outputs_from_tx($transaction) {
        if(!$transaction->isCoinbase()) {
            foreach ($transaction->getOutputs() as $output_key => $output) {
                $marker_output_payload = MarkerOutput::parse_script($output->getScript()->getBuffer());
                if (!is_null($marker_output_payload)) {
                    $marker_output = MarkerOutput::deserialize_payload($marker_output_payload);
                    $previous_outputs = array();

                    foreach ($transaction->getInputs() as $previous_input) {
                        $previous_outputs[] = self::get_output($previous_input->getOutpoint()->getTxId()->getHex(),$previous_input->getOutpoint()->getVout());
                    }
                   $asset_ids = self::compute_asset_ids($previous_outputs, $output_key, $transaction, $marker_output->get_asset_quantities());
                    if (!is_null($asset_ids)) {
                        return $asset_ids;
                    }
                }
            }
        }
 
        $colored_outputs = array();
        foreach ($transaction->getOutputs() as $output) {
            $colored_outputs[] = new OaTransactionOutput($output->getValue(), $output->getScript(), null, 0 ,OutputType::UNCOLORED);
        }
        return $colored_outputs;
    }
    /**
     * @param transaction         : MainchainのUTXO(以下transactionは全てUTXO)
     * @param previous_outputs    : marker outputを含むtransactionのinput(previous output)から作成したOaTransactionOutput
     * @param marker_output_index : transactionのなかのoutputでmarker outputを含むoutputのindex
     * @param asset_quantities    : marker outputに含まれるアセットの数
     */
    public function compute_asset_ids ($previous_outputs, $marker_output_index, $transaction, $asset_quantities) {
        $outputs = $transaction->getOutputs();

        //Marker output payloadが存在しているので、coinbaseではないし(previous_outputsが存在する)
        //transactionに含まれているopenassets操作のトランザクションの数以上、アセットの種類(count($asset_quantities))が存在する
        if (count($asset_quantities) > count($outputs) - 1 || count($previous_outputs) == 0) {
            return null;
        }
        $result = array();
        $marker_output = $outputs[$marker_output_index];
        //Maker outputを含むトランザクション群で一番最初のトランザクションはasset issueのトランザクション
        $issuance_asset_id = Util::script_to_asset_id($previous_outputs[0]->get_script(), $this->network);
        //marker output indexが1以上の場合それはアセットの発行を示す
        //issuance
        for ($i = 0 ; $i <= $marker_output_index -1 ; $i++) {
            $value = $outputs[$i]->getValue();
            $script = $outputs[$i]->getScript();

            //アセット数の種類はmarker outputより前のoutput数と同じ
            if ($i < count($asset_quantities) && $asset_quantities[$i] > 0) {
                $payload = MarkerOutput::parse_script($marker_output->getScript()->getBuffer());
                $metadata = MarkerOutput::deserialize_payload($payload)->get_metadata();


                //p2sh関連は現状未実装
                $param = null;
                if((is_null($metadata)  || strlen($metadata) == 0) && $previous_outputs[0]->get_script()->isP2SH($param) ) {
                    //$metadata = self::parse_issuance_p2sh_pointer($transaction-getInput(0)->getScript());
                    throw new Exception("p2sh is not supported");
                }
                if (is_null($metadata)) {
                    $metadata = "";
                }
                $output = new OaTransactionOutput($value, $script, $issuance_asset_id, $asset_quantities[$i] ,OutputType::ISSUANCE, $metadata);
            } else {
                $output = new OaTransactionOutput($value, $script, null, 0 ,OutputType::ISSUANCE);
            }
            $result[] = $output;
        }
        $result[] = new OaTransactionOutput($marker_output->getValue(), $marker_output->getScript(), null, 0 ,OutputType::MARKER_OUTPUT);

        $remove_outputs = array();
        for ($i = $marker_output_index + 1; $i <= count($outputs) - 1; $i++) {
            $marker_output_payload = MarkerOutput::parse_script($outputs[$i]->getScript()->getBuffer());
            if (!is_null($marker_output_payload)) {
                $remove_outputs[] = $outputs[$i];
                $result[] = new OaTransactionOutput($outputs[$i]->getValue(), $outputs[$i]->getScript(), null, 0 ,OutputType::MARKER_OUTPUT);
            }       
        }
        
        foreach ($remove_outputs as $delete_output) {
            if(($key = array_search($delete_output, $outputs)) !== false) {
                unset($outputs[$key]);
            }
        }

        $input_units_left = 0;
        $index = 0;
        for ($i = $marker_output_index + 1; $i <= count($outputs) - 1; $i++) {
            $output_asset_quantity = 0;
            if ($i <= count($asset_quantities)) {
                $output_asset_quantity = $asset_quantities[$i - 1];	
            } else {
                $output_asset_quantity = 0;
            }
            $output_units_left = $output_asset_quantity;
            $asset_id = null;
            $metadata = null;
            while($output_units_left > 0) {
                $index++;
                if ($input_units_left == 0) {
                    foreach ($previous_outputs as $current_input) {
                        $input_units_left = $current_input->get_asset_quantity();
                        if (!is_null($current_input->get_asset_id())) {
                            $progress = min([$input_units_left, $output_units_left]);
                            $output_units_left -= $progress; 
                            $input_units_left -= $progress; 
                            if (is_null($asset_id)) {
                                $asset_id = $current_input->get_asset_id();
                                $metadata = $current_input->get_metadata_url();
                            } else if ($asset_id != $current_input->get_asset_id()){
                                return null;
                            }
                        }
                    }
                }
            }
            $result[] = new OaTransactionOutput($outputs[$i]->getValue(), $outputs[$i]->getScript(), $asset_id , $output_asset_quantity ,OutputType::TRANSFER, $metadata);
        }
        return $result;
         
    }
 
    public function load_transaction($txid) {
        $decode_transaction = $this->provider->get_transaction($txid, 0);
        if (empty($decode_transaction)) {
            throw new Exception("txid : " . $txid ." could not be retrieved");
        }
        return $decode_transaction;
    }

    public function load_cached_transaction($tx_id) {
        if (is_null($this->transaction_cache)) {
            return self::load_transaction($tx_id);
        }
        $decode_transaction = $this->transaction_cache->get($tx_id);
        if (is_null($decode_transaction)){
            $decode_transaction = self::load_transaction($tx_id);
            $this->transaction_cache->set($tx_id, $decode_transaction);
        } 

        return $decode_transaction;
    }

    public function load_cached_output($tx_id, $index) {
        if(!is_null($this->output_cache)) {
            $cache = $this->output_cache->get($tx_id, $index);
            if (!is_null($cache)) {
                return $cache;
            } else {
                return false;
            }
        } else { 
            return false;
        }
    }

    public function set_cached_output($tx_id, $outputs) {
        if(!is_null($this->output_cache)) {
            foreach ($outputs as $key => $output) {
                $this->output_cache->set($tx_id, $key, $output->value, $output->get_script()->getHex(), $output->asset_id, $output->asset_quantity, $output->output_type, $output->metadata); 
            }
        } else {
            return false;
        }
    }

    public function create_transaction_builder() {
        if ($this->network->get("default_fee") == "auto") {
            $coin =  $this->provider->estimate_smartfee(1);
            $estimated_fee_rate = 100000;
            if (!empty($coin)) {
                $estimated_fee_rate = Util::coin_to_satoshi($this->provider->estimate_smartfee(1));
            }
            return new TransactionBuilder($this->network->get("dust_limit"), $estimated_fee_rate, $this->network);
        } else {
            return new TransactionBuilder($this->network->get("dust_limit"), $this->network->get("default_fee"), $this->network);
        }
    }

    public function process_transaction($transaction, $mode = "broadcast") {
        if ($mode == "broadcast" || $mode == "signed") {
            $sign_transaction = $this->provider->sign_transaction($transaction->getBaseSerialization()->getHex());
            $transaction_id = $this->provider->send_transaction($sign_transaction->hex);
        } else {
            return $transaction;
        }
        return $transaction_id;
    }


/*
    public function parse_issuance_p2sh_pointer($script) {
        $buffer = Buffer::hex($script);
        $script = new Script($buffer);
    }
*/
}
