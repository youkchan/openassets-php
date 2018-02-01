<?php
namespace youkchan\OpenassetsPHP\Transaction;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Transaction\TransactionOutput;
use youkchan\OpenassetsPHP\Transaction\TransferParameters;
use youkchan\OpenassetsPHP\Util;
use BitWasp\Bitcoin\Address\AddressCreator;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
use BitWasp\Bitcoin\Script\ScriptFactory;
use Exception;

class TransactionBuilder
{

    public $amount;
    public $estimated_fee_rate;
    public $network;

    public function __construct($amount = 600, $estimated_fee_rate = 10000, $network)
    {
        $this->amount = $amount;
        $this->estimated_fee_rate = $estimated_fee_rate;
        $this->network = $network;
    }

    public function issue_asset(TransferParameters $issue_spec, $metadata, $fee = null) {
        if (is_null($fee)) {
            // Calculate fee (assume that one vin and four vouts are wrote)
            $fee = self::calc_fee(1, 4);
        }
        $transaction = TransactionFactory::build();
        $uncolored_outputs = self::collect_uncolored_outputs($issue_spec->unspent_outputs, $this->amount * 2 + $fee);
        $inputs = $uncolored_outputs[0];
        $total_amount = $uncolored_outputs[1];

        $transaction = TransactionFactory::build();
        foreach ($inputs as $input) {
            $transaction->spendOutPoint($input->out_point(), $input->output()->get_script());
        }

        $issue_address  = Util::convert_oa_address_to_address($issue_spec->to_script);
        $from_address  = Util::convert_oa_address_to_address($issue_spec->change_script);
        $asset_quantities = [];
        $address_creator = new AddressCreator();
        foreach ($issue_spec->split_output_amount() as $amount) {
            $asset_quantities[] = $amount;
            $transaction->payToAddress($this->amount, $address_creator->fromString($issue_address, $this->network->get_bclib_network())); //getcoloredoutput
        }


        $transaction->outputs([self::get_marker_output($asset_quantities, $metadata)]); //getcoloredoutput
        $transaction->payToAddress($total_amount - $this->amount - $fee, $address_creator->fromString($from_address,$this->network->get_bclib_network())); //getuncoloredoutput
        $transaction = $transaction->get();
        return $transaction;
    }

    public function collect_uncolored_outputs($unspent_outputs, $amount)
    {
        $total_amount = 0;
        $results = [];
        foreach($unspent_outputs as $unspent_output) {
            if (is_null($unspent_output->output()->get_asset_id())) {
                $results[] = $unspent_output;
                $total_amount += $unspent_output->output()->get_value();
            }
            if ($total_amount >= $amount) {
                return [$results, $total_amount];
            } 
        }
        throw new Exception('Collect Uncolored Outputs went to Wrong');
    }  

    public static function get_marker_output($asset_quantities, $metadata = null)
    {
        $marker_output = new MarkerOutput($asset_quantities, $metadata);
        return new TransactionOutput(0, $marker_output->build_script());
    }
    
    public function calc_fee($inputs_num, $outputs_num) 
    {
        $tx_size = 148 * $inputs_num + 34 * $outputs_num + 10;
        return (1 + $tx_size / 1000) * $this->estimated_fee_rate;
    }


    public function create_uncolored_output($address, $value)
    {
        if ($value < $this->amount) {
            throw new Exception('DustOutputError');
        }
        $address_creator = new AddressCreator();
        $address = $address_creator->fromString($address, $this->network->get_bclib_network());
        return new TransactionOutput($value, $address->getScriptPubKey());
    }

    public function create_colored_output($address)
    {
        $address_creator = new AddressCreator();
        $address = $address_creator->fromString($address, $this->network->get_bclib_network());
        return new TransactionOutput($this->amount, $address->getScriptPubKey());
    }

    public static function collect_colored_outputs($unspent_outputs, $asset_id, $asset_quantity)
    {

        $total_amount = 0;
        $results = [];
        foreach($unspent_outputs as $unspent_output) {
            if ($unspent_output->output()->get_asset_id() == $asset_id) {
                $results[] = $unspent_output;
                $total_amount += $unspent_output->output()->get_asset_quantity();
            }
            if ($total_amount >= $asset_quantity) {
                return [$results, $total_amount];
            } 
        }
        throw new Exception('Collect Colored Outputs went to Wrong');
    }

    public function transfer_asset($asset_id, $asset_transfer_spec, $coin_change_script, $fee) {
        $coin_transfer_spec = new TransferParameters($asset_transfer_spec->unspent_outputs, null, Util::convert_oa_address_to_address($coin_change_script), 0, 1, $this->network->get_bclib_network());
        return $this->transfer([[$asset_id, $asset_transfer_spec]], [$coin_transfer_spec], $fee);
    }

    public function transfer($asset_transfer_specs, $coin_transfer_specs, $fee) {
        $inputs = []; //vin
        $outputs = []; //vout
        $asset_quantities = [];
        //Only when assets are transfered
        $asset_based_specs = [];
        foreach($asset_transfer_specs as $spec) {
            $asset_id = $spec[0];
            $transfer_spec = $spec[1];
            if (!array_key_exists($asset_id, $asset_based_specs)) {
                $asset_based_specs[$asset_id] = [];
            }
            if (!isset($asset_based_specs->change_script) || !array_key_exists($asset_id, $asset_based_specs->change_script)) {
                $asset_based_specs[$asset_id][$transfer_spec->change_script] = [];
            }
            $asset_based_specs[$asset_id][$transfer_spec->change_script][] = $transfer_spec;
        }

        foreach ($asset_based_specs as $asset_id => $address_based_specs) {
            foreach ($address_based_specs as $transfer_specs) {
                $transfer_amount = 0;
                foreach($transfer_specs as $transfer_spec) {
                    $transfer_amount += $transfer_spec->amount;
                }
                $result = self::collect_colored_outputs($transfer_specs[0]->unspent_outputs, $asset_id, $transfer_amount);
                $colored_outputs = $result[0];
                $total_amount = $result[1];
                foreach($colored_outputs as $colored_output) {
                    $inputs[] = $colored_output;
                }

                foreach($transfer_specs as $spec) {
                    foreach($spec->split_output_amount() as $amount) {
                        $outputs[] = self::create_colored_output(Util::convert_oa_address_to_address($spec->to_script));
                        $asset_quantities[] = $amount;
                    }
                }
                if ($total_amount > $transfer_amount) {
                    $outputs[] = self::create_colored_output(Util::convert_oa_address_to_address($transfer_specs[0]->change_script));
                    $asset_quantities[] = $total_amount - $transfer_amount;
                }
            }
        }

        $utxo = $coin_transfer_specs[0]->unspent_outputs; //check cloned
        # Calculate rest of bitcoins in asset settings
        # coin_excess = inputs(colored) total satoshi - outputs(transfer) total satoshi
        $coin_excess_input = 0;
        $coin_excess_output = 0;
        foreach($inputs as $input) {
            $coin_excess_input += $input->output->get_value();
        }
        foreach ($outputs as $output) {
            $coin_excess_output += $output->getValue(); 
        }
        $coin_excess = $coin_excess_input - $coin_excess_output;
        # Calculate total amount of bitcoins to send
        $coin_transfer_total_amount = 0;
        foreach ($coin_transfer_specs as $coin_transfer_spec) {
            $coin_transfer_total_amount += $coin_transfer_spec->amount;
        }
        if (is_null($fee)) {
            $fixed_fee = 0;
        } else {
            $fixed_fee = $fee;
        }

        if ($coin_excess < ($coin_transfer_total_amount + $fixed_fee)) {
          # When there does not exist enough bitcoins to send in the inputs
          # assign new address (utxo) to the inputs (does not include output coins)
            # CREATING INPUT (if needed)
            $result = self::collect_uncolored_outputs($utxo, $coin_transfer_total_amount + $fixed_fee - $coin_excess);
            $uncolored_outputs = $result[0];
            $uncolored_amount = $result[1];
            foreach ($uncolored_outputs as $uncolored_output) {
                if(($key = array_search($uncolored_output, $utxo)) !== false) {
                    unset($utxo[$key]);
                }
                $inputs[] = $uncolored_output;
            }
            $coin_excess += $uncolored_amount;
        }

        if (is_null($fee)) {
          $fee = $this->calc_fee(count($inputs), count($outputs) + count($coin_transfer_specs) + 1);
        }
        
        $change = $coin_excess - $coin_transfer_total_amount - $fee;
        
        if ($change > 0 && $change < $this->amount) {
            # When there exists otsuri, but it is smaller than @amount (default is 600 satoshis)
            # assign new address (utxo) to the input (does not include @amount - otsuri)
            # CREATING INPUT (if needed)
            $result =  self::collect_uncolored_outputs($utxo, $this->amount - $change);
            $uncolored_outputs = $result[0];
            $uncolored_amount = $result[1];
            foreach ($uncolored_outputs as $uncolored_output) {
                $inputs[] = $uncolored_output;
            }
            $change += $uncolored_amount;
        }
        
        if ($change > 0) {
            # When there exists otsuri, write it to outputs
            # CREATING OUTPUT
            $outputs[] = self::create_uncolored_output($coin_transfer_specs[0]->change_script, $change);
        }

        foreach ($coin_transfer_specs as $coin_transfer_spec) {
            if ($coin_transfer_spec->amount > 0) {
                # Write output for bitcoin transfer by specifics of the argument
                # CREATING OUTPUT
                foreach ($coin_transfer_spec->split_output_amount() as $amount) {
                  $outputs[] = self::create_uncolored_output($coin_transfer_spec->to_script, $amount);
                }
            }
        }
    
        if (!empty($asset_quantities)) {
            array_unshift($outputs, self::get_marker_output($asset_quantities));
        }
        
        $transaction = TransactionFactory::build();
        foreach(Util::array_flatten($inputs) as $input) {
            $transaction->spendOutPoint($input->out_point(), $input->output()->get_script());
        }
        $transaction->outputs($outputs);

        $transaction = $transaction->get();
        return $transaction;

    }

}
