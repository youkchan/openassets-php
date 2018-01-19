<?php

function get_run_coin_name(){
    exec('ps aux', $ps);
    
    $run_coin_name ="";
    foreach ($ps as $item) {
        if(strpos($item, "./litecoind")) {
            $run_coin_name = "litecoin";
        }else if(strpos($item, "./monacoin")) {
            $run_coin_name = "monacoin";
        }
    }
    return $run_coin_name;
}




