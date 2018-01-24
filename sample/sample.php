<?php
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

require_once "../vendor/autoload.php";
use youkchan\OpenassetsPHP\Openassets;

//クイックサンプルです。フルノードが起動していれば、このサンプルを使ってassetの発行ができます

//現在はmonacoin,litecoinのtestnetのみ対応しています
//自身のmonacoind,litecoindのrpcに関する情報を入力してください。
$setting = array(
    "rpc_user" => "mona",
    "rpc_password" => "mona",
    "rpc_port" => 19402
);

$openassets = new Openassets($setting);

//utxoが存在するアドレスであれば、get_balanceで残高が取得でき
//Openassets用のaddress(oa_address)が取得できます。
//assetのやり取りはoa_addressを使って実施します
//assetが発行済みであれば、そのassetのasset_idも取得できます。これはassetのsendの時に必要になります。
var_dump($openassets->get_balance());

$from_oa_address = "bWuEUSQbcx5gKTXkr6mnzBWN37WSyLEaXQf";

//発行するアセットの量です
$quantity = 600;
//metadataの説明は後日記載
$metadata = "u=http://google.com";

//mainchainの手数料です
$fee = 50000;

//var_dump($openassets->issue_asset($from_oa_address,$quantity, $metadata,null ,$fee));


$to_oa_address = "bXA27xniKb4TXGadndUUhV1vVCFpqPLQHZN";
$asset_id = "oWDNLde2LweGTgsVgtx6XcNNDZvm8kWnj1";

//var_dump($openassets->send_asset($from_oa_address,$asset_id , $quantity, $to_oa_address, $fee));

