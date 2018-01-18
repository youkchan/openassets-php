<?php
namespace youkchan\OpenassetsPHP\Transaction;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Transaction\TransactionOutput;
use youkchan\OpenassetsPHP\Transaction\TransferParameters;
use youkchan\OpenassetsPHP\Util;
use BitWasp\Bitcoin\Address\AddressCreator;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;

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
            // Calculate fees (assume that one vin and four vouts are wrote)
            $fee = self::calc_fee(1, 4);
        }
        $transaction = TransactionFactory::build();
        $uncolored_outputs = self::collect_uncolored_outputs($issue_spec->unspent_outputs, $this->amount * 2 + $fee);
        $inputs = $uncolored_outputs[0];
        $total_amount = $uncolored_outputs[1];
        foreach ($inputs as $input) {
            //$transaction = $transaction->spendOutPoint($input->out_point(), $input->output()->get_script());
            $transaction->spendOutPoint($input->out_point(), $input->output()->get_script());
        }
//var_dump($issue_spec);
//var_dump($issue_spec->change_script);

        $issue_address  = Util::convert_oa_address_to_address($issue_spec->to_script);
        $from_address  = Util::convert_oa_address_to_address($issue_spec->change_script);
        $asset_quantities = [];
        foreach ($issue_spec->split_output_amount() as $amount) {
            $asset_quantities[] = $amount;
            $address_creator = new AddressCreator();
            //$transaction = $transaction->payToAddress($this->amount, $address_creator->fromString($issue_address)); //getcoloredoutput
            $transaction->payToAddress($this->amount, $address_creator->fromString($issue_address, $this->network->get_bclib_network())); //getcoloredoutput
        }

//var_dump($transaction);

        $transaction->outputs([self::get_marker_output($asset_quantities, $metadata)]); //getcoloredoutput
        $address_creator = new AddressCreator();
        $transaction->payToAddress($total_amount - $this->amount - $fee, $address_creator->fromString($from_address,$this->network->get_bclib_network())); //getuncoloredoutput
        $transaction->get();
var_dump($transaction);
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


}
