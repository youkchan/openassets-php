<?php
namespace youkchan\OpenassetsPHP\Transaction;
use youkchan\OpenassetsPHP\Util;
use Exception;

class TransferParameters
{

    public $unspent_outputs;
    public $amount;
    public $change_script;
    public $to_script;
    public $output_quantity;
    public $network;
    
    public function __construct($unspent_outputs, $to_script, $change_script, $amount, $output_quantity = 1, $network)
    {
        $this->unspent_outputs = $unspent_outputs;
        $this->to_script = $to_script;
        $this->change_script = $change_script;
        $this->amount = $amount;
        $this->output_quantity = $output_quantity;
        $this->network = $network;
    }

    public function validate_address($both = "both") {
        $addresses = [];
        if($both == "both") {
            $addresses[]  = Util::convert_oa_address_to_address($this->to_script);
            $addresses[] = Util::convert_oa_address_to_address($this->change_script);
        } else if ($both == "from") {
            $addresses[] = Util::convert_oa_address_to_address($this->change_script);
        } else if ($both == "to") {
            $addresses[]  = Util::convert_oa_address_to_address($this->to_script);
        }
        Util::validate_addresses($addresses, $this->network);
    }

    public function split_output_amount()
    {
        $split_amounts = [];
        for ($i = 0; $i <= $this->output_quantity -1 ; $i++) {
          if ($i == $this->output_quantity - 1) {
            $value = $this->amount / $this->output_quantity+ $this->amount % $this->output_quantity;
          } else {
            $value = $this->amount / $this->output_quantity;
          }
          $split_amounts[] = $value;
        }
        return $split_amounts;
    }
}
