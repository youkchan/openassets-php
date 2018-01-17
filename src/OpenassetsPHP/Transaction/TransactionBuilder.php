<?php
namespace youkchan\OpenassetsPHP\Transaction;

class TransctionBuilder
{

    public $amount;
    public $efr;

    public function __construct($amount = 600, $efr = 10000)
    {
        $this->amount = $amount;
        $this->efr = $efr;
    }

}
