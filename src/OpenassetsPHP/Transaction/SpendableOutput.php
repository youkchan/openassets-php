<?php
namespace youkchan\OpenassetsPHP\Transaction;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use youkchan\OpenassetsPHP\Transaction\OaOutPoint;
use BitWasp\Bitcoin\Transaction\OutPoint;
use BitWasp\Buffertools\Buffer;

class SpendableOutput
{
    public $oa_out_point;
    public $output;
    public $confirmations;
    public $spendable;
    public $solvable;
    public function __construct(OaOutPoint $oa_out_point, OaTransactionOutput $output)
    {
        $this->oa_out_point = $oa_out_point;
        $this->output = $output;
        $this->confirmations = null;
        $this->spendable = null;
        $this->solvable = null;
    }

    public function output()
    {
        return $this->output;
    }

    public function oa_out_point()
    {
        return $this->oa_out_point;
    }

    public function out_point()
    {
        return new OutPoint(Buffer::hex($this->oa_out_point->hash), $this->oa_out_point->index);
    }
  
}
