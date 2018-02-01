<?php


function get_run_coin_name(){
    exec('ps aux', $ps);
    
    $run_coin_name ="";
    foreach ($ps as $item) {
        if(strpos($item, "./litecoind -testnet")) {
            $run_coin_name = "litecointestnet";
        }else if(strpos($item, "./monacoind -testnet")) {
            $run_coin_name = "monacointestnet";
        }
    }
    return $run_coin_name;
}




