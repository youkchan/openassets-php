<?php
namespace youkchan\OpenassetsPHP\Transaction;

class TransferParameters
{

    public $unspent_outputs;
    public $amount;
    public $change_script;
    public $to_script;
    public $output_quantity;
    
    public function __construct($unspent_outputs, $to_script, $change_script, $amount, $output_quantity = 1)
    {
        $this->unspent_outputs = $unspent_outputs;
        $this->to_script = $to_script;
        $this->change_script = $change_script;
        $this->amount = $amount;
        $this->output_quantity = $output_quantity;
    }

}
