<?php
namespace youkchan\OpenassetsPHP;
use BitWasp\Bitcoin\Base58;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\Buffertools;
use BitWasp\Bitcoin\Address\AddressCreator;
use Exception;

class Util
{
    public static function convert_oa_address_to_address($oa_address) {
        $decode_address = Base58::decode($oa_address);
        $btc_address = $decode_address->slice(1, -4);
        $btc_checksum = Base58::checksum($btc_address);
        return Base58::encode(Buffertools::concat($btc_address , $btc_checksum));
    }

    public static function convert_address_to_oa_address($oa_address_list) {
    }

    public static function validate_addresses($address_list, $network) {

        //TODO bitcoin-rubyのvalid_addressに対応していると思われるが要検証
        //TODO 最新バージョンだと大幅に変更されている.現状デフォルトでインストールされる安定バージョンを利用
        $address_creator = new AddressCreator();
        foreach ($address_list as $address) {
            try {
                $address_creator->fromString($address, $network);
            } catch (Exception $e){
                throw new Exception($address . " is invalid bitcoin address" );
            }
        }
    }
}
