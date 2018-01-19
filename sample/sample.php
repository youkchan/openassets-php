<?php
require_once "../vendor/autoload.php";
use youkchan\OpenassetsPHP\Openassets;

$openassets = new Openassets();

var_dump($openassets->list_unspent());

$from_oa_address = "";
$amount = 0;
$metadata = "";
$fee = 50000;

var_dump($this->openassets->issue_asset($from_oa_address,$amount, $metadata,null ,$fee));
