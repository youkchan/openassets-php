<?php
require_once "../vendor/autoload.php";
use youkchan\OpenassetsPHP\Openassets;

$openassets = new Openassets();

//var_dump($openassets->list_unspent(["bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK"]));

$from_oa_address = "bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK";
$amount = 600;
$metadata = "http://google.com";
$fee = 50000;

var_dump($openassets->issue_asset($from_oa_address,$amount, $metadata,null ,$fee));


$to_oa_address = "";
$asset_id = "";

var_dump($openassets->send_asset($from_oa_address,$asset_id , $amount, $to_oa_address, $fee));
